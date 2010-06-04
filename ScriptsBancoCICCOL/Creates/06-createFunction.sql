/* Desenvolvido em 16 de maio de 2010 - CriaÁ„o das funÁıes do CICCOL */

/* Funcao para retornar as solicitaÁıes pendentes de cadastro no sistema */
CREATE TYPE solicitacoes AS (dt_solicitacao TIMESTAMP, nome VARCHAR(15), sobrenome VARCHAR(30));

CREATE OR REPLACE FUNCTION FuncSolicitacoesCadastro() RETURNS SETOF solicitacoes AS
'
	SELECT dt_solicitacao, nome, sobrenome FROM Usuario WHERE admmod_matricula IS NULL ORDER BY dt_solicitacao;
'
LANGUAGE 'SQL';

/* Funcao para aprovar ou reprovar a solicitacao de cadastro no sistema */
CREATE OR REPLACE FUNCTION VerificaSolicitacaoCadastSis(VARCHAR(15), VARCHAR(70)) RETURNS SMALLINT AS
$$
DECLARE
usu_matricula ALIAS FOR $1;
usu_senha ALIAS	FOR $2;
id_usuario INTEGER;

BEGIN
	/* Se existir algum usu·rio (aluno, docente, administrador e moderador com o n˙mero de matrÌcula da solicitaÁ„o, ent„o insere o n˙mero de matrÌcula na tabela Autenticacao) */
	IF EXISTS(SELECT * FROM Docente WHERE matricula=usu_matricula) THEN
		SELECT id_user FROM Docente WHERE matricula=usu_matricula INTO id_usuario;
        ELSEIF EXISTS(SELECT * FROM Aluno WHERE matricula=usu_matricula) THEN
		SELECT id_user FROM Aluno WHERE matricula=usu_matricula INTO id_usuario;
	ELSEIF EXISTS(SELECT * FROM AdminModerador WHERE matricula=usu_matricula) THEN
		SELECT id_user FROM AdminModerador WHERE matricula=usu_matricula INTO id_usuario;
	ELSE
		RETURN 0;	/* 0 (zero) significa que n„o foi encontrado um usu·rio cadastrado com esse n˙mero de matrÌcula, ent„o a solicitaÁ„o n„o pode ser aprovada. */
	END IF;

	INSERT INTO Autenticacao(identificador, senha, id_user) VALUES (usu_matricula, usu_senha, id_usuario);

	RETURN 1; /* 1 significa que foi encontrado um usu·rio com a matrÌcula digitada, ent„o a solicitaÁ„o foi aprovada. */
END;
$$
LANGUAGE plpgsql;


/* Funcao para alterar a notÌcia de pendente para aprovada */
CREATE TYPE noticiaResultado AS (texto VARCHAR, assunto VARCHAR, dt_adicionada TIMESTAMP, nome VARCHAR(50), email VARCHAR(30));

CREATE OR REPLACE FUNCTION FuncAprovaNoticia(INT) RETURNS SETOF noticiaResultado AS
'

	UPDATE Noticia SET situacao = 1, dt_resultado = CURRENT_DATE WHERE id = $1;

	SELECT texto, assunto, dt_adicionada, nome, email FROM Noticia N JOIN Autor A ON N.id_autor = A.id_autor WHERE id = $1;

'	
LANGUAGE 'SQL';

/* Funcao para alterar a notÌcia de pendente para aprovada */

CREATE OR REPLACE FUNCTION FuncReprovaNoticia(INT) RETURNS SETOF noticiaResultado AS
'

	UPDATE Noticia SET situacao = 2, dt_resultado = CURRENT_DATE WHERE id = $1;

	SELECT texto, assunto, dt_adicionada, nome, email FROM Noticia N JOIN Autor A ON N.id_autor = A.id_autor WHERE id = $1;

'	
LANGUAGE 'SQL';

/* FunÁ„o para a recuperaÁ„o de senha em caso de solicitaÁ„o - A senha È gerada automaticamente*/
CREATE OR  REPLACE FUNCTION  FuncRecupSenha(VARCHAR(15)) RETURNS VARCHAR(50) AS
$$
DECLARE
identif ALIAS FOR $1;
senha_aleatoria   VARCHAR;

BEGIN
	IF EXISTS (SELECT identificador FROM Autenticacao WHERE identificador=identif) THEN
                /* Cria a senha aleatÛria para ser enviada para o email do solicitador*/
		SELECT senha_aleatoria = CAST(1 + ROUND(RANDOM()*999) AS VARCHAR(3)) || CAST(1 + ROUND(RANDOM()*999)AS VARCHAR(3)) || CAST(1 + ROUND(RANDOM()*999)AS VARCHAR(3)) || CAST(1 + ROUND(RANDOM()*999)AS VARCHAR(3));
	
		/* Atualiza no banco a nova senha com o algoritmo MD5 */
		UPDATE Autenticacao SET senha = MD5(senha_aleatoria) WHERE identificador=identif;
	END IF;
	RETURN senha_aleatoria; 
END;
$$
LANGUAGE plpgsql;

/* FunÁ„o para alterar a senha */
CREATE OR  REPLACE FUNCTION  FuncAlterarSenha(VARCHAR(15), VARCHAR(50), VARCHAR(50), VARCHAR(50)) RETURNS INTEGER AS
$$
DECLARE
identif 		ALIAS FOR $1;
senha_atual 		ALIAS FOR $2;
nova_senha 		ALIAS FOR $3;
confirmacao_senha 	ALIAS FOR $4;
flag 			INTEGER;   --Retona zero se houve erro relacionado a senha atual ser diferente da digitada, um houve diferenÁa nas senhas digitadas e
				   --dois se a senha foi atualizada com sucesso. SÛ tratar na aplicaÁ„o.

BEGIN

	IF MD5(senha_atual) = (SELECT senha FROM Autenticacao WHERE identificador = identif) THEN
		IF MD5(nova_senha) = MD5(confirmacao_senha) THEN
			UPDATE Autenticacao SET senha = MD5(nova_senha)   WHERE identificador = identif;
			flag = 2;
		ELSE
			flag = 1;
		END IF;
	ELSE
		flag = 0;
	END IF;

	RETURN 	flag;
END;

$$
LANGUAGE plpgsql;

/* FunÁ„o para retornar as disciplinas ministradas por determinado professor */
CREATE TYPE docente_disciplina AS (doc_nome VARCHAR(15), doc_sobrenome VARCHAR(100), cod_disciplina VARCHAR(10), disc_nome VARCHAR(50), disc_turma SMALLINT);

CREATE OR REPLACE FUNCTION FuncListaDocDisciplina(INT) RETURNS SETOF docente_disciplina AS
'

	SELECT doc_nome, doc_sobrenome, cod_disciplina, disc_nome, disc_turma FROM ViewDocenteDisciplina WHERE id_user = $1;

'	
LANGUAGE 'SQL';

/* FunÁ„o para retornar as disciplinas cursadas por determinado aluno */
CREATE TYPE aluno_disciplina AS (alu_nome VARCHAR(15), alu_sobrenome VARCHAR(100), alu_disciplina VARCHAR(10), disc_nome VARCHAR(50), disc_turma SMALLINT);

CREATE OR REPLACE FUNCTION FuncListaAlunoDisciplina(INT) RETURNS SETOF docente_disciplina AS
'

	SELECT alu_nome, alu_sobrenome, alu_disciplina, disc_nome, disc_turma FROM ViewAlunoDisciplina WHERE id_user = $1;

'	
LANGUAGE 'SQL';

/* FunÁ„o para calcular a idade do aluno */
CREATE  OR REPLACE FUNCTION calcIdade(TIMESTAMP) RETURNS INT AS 
$$
DECLARE
  dtnasc ALIAS FOR $1;
  dia INT;  
  mes INT;  
  ano INT;
BEGIN
  dia := extract(day   from dtnasc);
  mes := extract(month from dtnasc);
  ano := extract(year  from dtnasc);
  IF dia > extract(day from CURRENT_DATE) THEN
      mes := mes + 1;
  END IF;
  IF mes > extract(month from CURRENT_DATE) THEN
     ano := ano + 1;
  END IF;
  RETURN extract(year from CURRENT_DATE) - ano;
END;
$$ LANGUAGE plpgsql; 

/* Funcao para formatar a data */
CREATE OR REPLACE FUNCTION FuncFormataData(TIMESTAMP) RETURNS VARCHAR AS
$$
DECLARE
   data 	ALIAS FOR $1;
   dia 		VARCHAR(2);
   mes 		VARCHAR(2);
   ano 		VARCHAR(4);
   dt_formatada VARCHAR;
   qt_char_dia	INT;
   qt_char_mes	INT;

BEGIN
   dia := CAST(EXTRACT(DAY FROM data) AS VARCHAR(2));
   mes := CAST(EXTRACT(MONTH FROM data) AS VARCHAR(2));
   ano := CAST(EXTRACT(YEAR FROM data) AS VARCHAR(4));

   SELECT CHAR_LENGTH(dia) INTO qt_char_dia; 
   SELECT CHAR_LENGTH(mes) INTO qt_char_mes; 

   IF (qt_char_dia = 1 AND qt_char_mes = 1) THEN
	dt_formatada := '0' || dia  || '/' || '0' || mes || '/' || ano;
   ELSEIF (qt_char_dia = 1 AND qt_char_mes = 2) THEN
	dt_formatada := '0' || dia || '/' || mes || '/' || ano;
   ELSEIF (qt_char_dia = 2 AND qt_char_mes = 1) THEN
	dt_formatada := dia || '/' || '0' || mes || '/' || ano;
   ELSE 
	dt_formatada := dia || '/' || mes || '/' || ano;
   END IF;

   RETURN dt_formatada;

END;
$$
LANGUAGE plpgsql; 

/* Funcao para verificar a acentuaÁ„o. */
CREATE OR REPLACE FUNCTION "public"."sem_acentos" (VARCHAR) RETURNS varchar AS
$$
	SELECT TRANSLATE($1, '·ÈÌÛ˙‡ËÏÚ˘„ı‚ÍÓÙÙ‰ÎÔˆ¸Á¡…Õ”⁄¿»Ã“Ÿ√’¬ Œ‘€ƒÀœ÷‹«', 'aeiouaeiouaoaeiooaeioucAEIOUAEIOUAOAEIOOAEIOUC');
$$
LANGUAGE 'SQL' VOLATILE CALLED ON NULL INPUT SECURITY INVOKER;





