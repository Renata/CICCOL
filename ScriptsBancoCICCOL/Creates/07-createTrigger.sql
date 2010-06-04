/* Desenvolvido em 17 de maio de 2010 - Criação dos gatilhos para verificar a integridade dos dados do CICCOL */

/* Tabela Usuario */
/* Lança uma exceção se a data de nascimento for cadastrada errada. Considera a idade mínima 15 anos. */
CREATE OR REPLACE FUNCTION FuncDataNascimento() RETURNS TRIGGER LANGUAGE PLPGSQL AS
$$
DECLARE
   idade INTEGER;

BEGIN
	idade = calcIdade(new.dt_nasc);
	IF (idade < 15) THEN
		RAISE EXCEPTION 'A data de nascimento não é válida!';
	END IF;

	RETURN NEW;
END;
$$;

CREATE TRIGGER tg_VerificaDataNasc BEFORE INSERT ON Usuario FOR EACH ROW EXECUTE PROCEDURE FuncDataNascimento();

/* Tabela Noticia */
/* Lança quando a situação da notícia é alterada, a data corrente é atribuida a data do resultado */
CREATE OR REPLACE FUNCTION FuncDataResultado() RETURNS TRIGGER LANGUAGE PLPGSQL AS
$$
BEGIN
	IF  (new.situacao <> 3) THEN
		new.dt_resultado = CURRENT_DATE;
	END IF;

	RETURN NEW;
END;
$$;

CREATE TRIGGER tg_AtualizaDataResultado BEFORE UPDATE ON Noticia FOR EACH ROW EXECUTE PROCEDURE FuncDataResultado();


/* Tabela Aluno */
/* Gatilho para inserir o semestre do aluno quando ele é cadastrado */
CREATE OR REPLACE FUNCTION FuncInsereSemestre() RETURNS TRIGGER LANGUAGE PLPGSQL AS
$$
DECLARE 
qtd_ano  	INTEGER;

BEGIN
	qtd_ano = (EXTRACT(YEAR FROM CURRENT_DATE) - NEW.ano_entrada) + 1;

	IF NEW.ano_entrada < EXTRACT(YEAR FROM CURRENT_DATE) THEN
		IF ( (1 <= EXTRACT(MONTH FROM CURRENT_DATE)) AND (EXTRACT(MONTH FROM CURRENT_DATE) < 8) ) THEN
			NEW.semestre = (2*qtd_ano) - NEW.semestre_entrada;
		ELSE IF ( (8 <= EXTRACT(MONTH FROM CURRENT_DATE)) AND (EXTRACT(MONTH FROM CURRENT_DATE) < 12)  ) THEN
			IF semest_entrada = 1 THEN
				NEW.semestre = (2*qtd_ano);
			ELSE
				NEW.semestre = (2*qtd_ano) - 1;
			END IF;
		END IF;
	END IF;
	ELSEIF NEW.ano_entrada = EXTRACT(YEAR FROM CURRENT_DATE) THEN
		IF ( (1 <= EXTRACT(MONTH FROM CURRENT_DATE)) AND (EXTRACT(MONTH FROM CURRENT_DATE) < 8) ) THEN
			IF NEW.semestre_entrada = 1 THEN
				NEW.semestre = 1;
			ELSE
				NEW.semestre = 0;
			END IF;
		END IF;
		ELSE IF ( (8 <= EXTRACT(MONTH FROM CURRENT_DATE)) AND (EXTRACT(MONTH FROM CURRENT_DATE) < 12)  ) THEN
			IF NEW.semestre_entrada = 1 THEN
				NEW.semestre = 2;
			ELSE
				NEW.semestre = 1;
			END IF;
		END IF;
	END IF;
	
	RETURN NEW;
END;
$$;

CREATE TRIGGER tg_FuncInsereSemestre BEFORE INSERT OR UPDATE ON Aluno FOR EACH ROW EXECUTE PROCEDURE FuncInsereSemestre();









