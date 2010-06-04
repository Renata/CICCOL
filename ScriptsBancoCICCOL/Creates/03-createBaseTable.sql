/* Desenvolvido em 13 de maio de 2010 - Criação do base de dados do CICCOL */

/* Cria a tabela AdminModerador - Herda da classe usuario */
CREATE TABLE  AdminModerador(

  matricula 	     VARCHAR(15)       CHECK (CHAR_LENGTH('matricula')>8)   NOT NULL,
  id_user 	     INTEGER           UNIQUE NOT NULL,

  PRIMARY KEY(matricula)

);


/* Cria a tabela Aluno - Herda da classe usuario */
CREATE TABLE  Aluno(

  matricula 	     VARCHAR(15)       CHECK (CHAR_LENGTH('matricula')>8)   NOT NULL,
  semestre 	     INTEGER,
  semestre_entrada   semest_entrada    NOT NULL,        
  ano_entrada        INTEGER           NOT NULL,
  situacao	     tipo_situacao     DEFAULT 1  NOT NULL,
  id_user 	     INTEGER           UNIQUE NOT NULL,

  PRIMARY KEY(matricula)

);

/* Cria tabela AlunoAreaInteresse*/
CREATE TABLE AlunoAreaInteresse(

   alu_matricula        VARCHAR(15)   NOT NULL,
   id_interesse         INTEGER       NOT NULL,

   PRIMARY KEY  (alu_matricula, id_interesse)
);

/* Cria tabela AlunoDisciplina*/
CREATE TABLE AlunoDisciplina(

   alu_matricula    VARCHAR(15)   NOT NULL,
   cod_disciplina   VARCHAR(10)   NOT NULL,
   nome             VARCHAR(30)   NOT NULL,
   turma            tipo_turma    NOT NULL,

   PRIMARY KEY  (alu_matricula, cod_disciplina, nome, turma)
);

/* Cria tabela AreaInteresse*/
CREATE TABLE AreaInteresse(

   id_interesse	    INTEGER   DEFAULT NEXTVAL('seq_id_interesse')   NOT NULL,
   descricao	    VARCHAR   NOT NULL		UNIQUE,

   PRIMARY KEY  (id_interesse)
);

/* Cria tabela AreaInteresseCurriculo*/
CREATE TABLE AreaInteresseCurriculo(

   id_curriculo     INTEGER   NOT NULL,
   id_interesse	    INTEGER   NOT NULL,

   PRIMARY KEY  (id_curriculo, id_interesse)
);

/* Cria a tabela Autenticacao */
CREATE TABLE  Autenticacao(

  identificador          VARCHAR(15)   NOT NULL,        
  senha                  VARCHAR(70),
  dt_acesso              TIMESTAMP,
  id_user                INTEGER       NOT NULL,
  id_tpuser              INTEGER       NOT NULL,
 
  PRIMARY KEY  (identificador)

);

/* Cria tabela Autor*/
CREATE TABLE Autor(

   id_autor       INTEGER       DEFAULT NEXTVAL('seq_id_autor') NOT NULL,
   nome	          VARCHAR(100)  NOT NULL,
   email          VARCHAR(50)   NOT NULL,

   PRIMARY KEY  (id_autor)
);


/* Cria a tabela Banner */
CREATE TABLE  Banner(

  id 			   INTEGER       DEFAULT NEXTVAL('seq_id_banner')   NOT NULL,        
  nome         	           VARCHAR(80)   NOT NULL,
  tipo          	   VARCHAR(80)   NOT NULL,
  tamanho       	   VARCHAR(80)   NOT NULL,
  dados         	   lo,
  admmod_matricula         VARCHAR(15)   NOT NULL,
  
  PRIMARY KEY  (id)

);

/* Cria tabela CalendarioEventos*/
CREATE TABLE CalendarioEventos(

   id 		                 INTEGER       DEFAULT NEXTVAL('seq_id_calendario_eventos')   NOT NULL,        
   dt_inicio     		 TIMESTAMP     NOT NULL,
   dt_fim         		 TIMESTAMP     NOT NULL,
   site			         VARCHAR(50),
   descricao          		 VARCHAR(50)   NOT NULL,
   admmod_matricula              VARCHAR(15)       NOT NULL,

   PRIMARY KEY  (id)
);

/* Cria tabela CalendarioSemestre*/
CREATE TABLE CalendarioSemestre(

   dt_inicio     		 TIMESTAMP     NOT NULL,
   dt_fim         		 TIMESTAMP     NOT NULL,
   dt_inic_prova_final      	 TIMESTAMP,
   dt_fim_prova_final      	 TIMESTAMP,
   descricao          		 VARCHAR       NOT NULL,
   admmod_matricula              VARCHAR(15)   NOT NULL,

   PRIMARY KEY  (dt_inicio, dt_fim)
);

/* Cria tabela Cargo*/
CREATE TABLE Cargo(

   id_cargo	     INTEGER       DEFAULT NEXTVAL('seq_id_cargo') NOT NULL,
   descricao         VARCHAR(80)   NOT NULL,

   PRIMARY KEY  (id_cargo)
);


/* Cria tabela Contato*/
CREATE TABLE Contato(

   id 	                    INTEGER        DEFAULT NEXTVAL('seq_id_contato')       NOT NULL,
   nome	                    VARCHAR(100)   NOT NULL,
   telefone                 VARCHAR(8)     NOT NULL,
   email                    VARCHAR(50)    NOT NULL,
   admmod_matricula         VARCHAR(15)    NOT NULL,

   PRIMARY KEY  (id)
);

/* Cria tabela Curriculo*/
CREATE TABLE Curriculo(

   id_curriculo 	       INTEGER       DEFAULT NEXTVAL('seq_id_curriculo')    NOT NULL,
   ultimo_emprego	       VARCHAR,
   perfil_profissional         VARCHAR(300)  NOT NULL,
   doc_matricula               VARCHAR(15)   NOT NULL   UNIQUE,
   id_cargo_atual              INTEGER,
   

   PRIMARY KEY  (id_curriculo)
);

/* Cria tabela Disciplina*/
CREATE TABLE Disciplina(

   cod_disciplina           VARCHAR(10)    NOT NULL,
   nome                     VARCHAR(80)    NOT NULL,
   turma    	            tipo_turma     NOT NULL,
   carga_horaria            INTEGER        NOT NULL,
   qtd_max_aluno            INTEGER        NOT NULL,
   num_cred                 INTEGER        NOT NULL,
   semestre                 INTEGER        NOT NULL,
   doc_matricula            VARCHAR(15),
   id_materia               INTEGER        NOT NULL,
   id_ementa		    INTEGER,
   optativa		    opt_disciplina DEFAULT 1 NOT NULL,
   id_disciplina	    INTEGER        DEFAULT NEXTVAL('seq_id_disciplina')   NOT NULL   UNIQUE,

   PRIMARY KEY  (cod_disciplina, nome, turma)
);


/* Cria tabela DisciplinaHorario*/
CREATE TABLE DisciplinaHorario(

   id_disciplinahorario	    INTEGER        DEFAULT NEXTVAL('seq_id_disciplinahorario')   NOT NULL   UNIQUE,
   dia_semana         	    VARCHAR(30)    NOT NULL,
   cod_disciplina     	    VARCHAR(10)    NOT NULL,
   nome               	    VARCHAR(80)    NOT NULL,
   turma              	    tipo_turma     NOT NULL,
   hora_inicio        	    TIME	   NOT NULL,
   hora_fim                 TIME	   NOT NULL,

   PRIMARY KEY  (dia_semana, cod_disciplina, nome, turma, hora_inicio, hora_fim)
);

/* Cria tabela DisciplinaEmail*/
CREATE TABLE DisciplinaEmail(

   id 	              INTEGER        NOT NULL,
   cod_disciplina     VARCHAR(10)    NOT NULL,
   nome               VARCHAR(80)    NOT NULL,
   turma              tipo_turma     NOT NULL,

   PRIMARY KEY  (id, cod_disciplina, nome, turma)
);

/* Cria tabela DisciplinaFluxograma*/
CREATE TABLE DisciplinaFluxograma(

   cod_disciplina           VARCHAR(10)    NOT NULL,
   nome                     VARCHAR(80)    NOT NULL,
   turma    	            tipo_turma     NOT NULL,
   id_fluxograma            INTEGER        NOT NULL,

   PRIMARY KEY  (id_fluxograma, cod_disciplina, nome, turma)
);

/* Cria tabela DisciplinaGradeCurricular*/
CREATE TABLE DisciplinaGradeCurricular(

   cod_disciplina           VARCHAR(10)    NOT NULL,
   nome                     VARCHAR(80)    NOT NULL,
   turma    	            tipo_turma     NOT NULL,
   id_grade                 INTEGER        NOT NULL,

   PRIMARY KEY  (id_grade, cod_disciplina, nome, turma)
);


/* Cria tabela DisciplinaRequisitaDisciplina*/
CREATE TABLE DisciplinaRequisitaDisciplina(

   id_discrequisitadisc			    INTEGER        DEFAULT NEXTVAL('seq_id_discrequisitadisc')   NOT NULL,
   cod_disciplina                 	    VARCHAR(10)    NOT NULL,
   nome                           	    VARCHAR(80)    NOT NULL,
   turma                          	    tipo_turma     NOT NULL,
   requisito_cod_disciplina       	    VARCHAR(10)    NOT NULL,
   requisito_nome                 	    VARCHAR(80)    NOT NULL,
   requisito_turma                	    tipo_turma     NOT NULL,

   PRIMARY KEY  (cod_disciplina, nome, turma, requisito_cod_disciplina, requisito_nome, requisito_turma)
);


/* Cria tabela DisciplinaSms*/
CREATE TABLE DisciplinaSms(

   id 	              INTEGER        NOT NULL,
   cod_disciplina     VARCHAR(10)    NOT NULL,
   nome               VARCHAR(80)    NOT NULL,
   turma              tipo_turma     NOT NULL,

   PRIMARY KEY  (id, cod_disciplina, nome, turma)
);

/* Cria a tabela Docente - Herda da classe usuario */
CREATE TABLE  Docente(

  matricula 	   VARCHAR(15)       CHECK (CHAR_LENGTH('matricula')>8)  NOT NULL,
  id_sala          INTEGER,
  id_user 	   INTEGER           UNIQUE  NOT NULL,

  PRIMARY KEY(matricula)

);

/* Cria tabela Email*/
CREATE TABLE Email(

   id 	            	INTEGER       DEFAULT NEXTVAL('seq_id_email')   NOT NULL,
   texto            	VARCHAR       NOT NULL,
   assunto          	VARCHAR(30),
   remetente        	VARCHAR(30)   NOT NULL,
   destinatario     	VARCHAR(30)   NOT NULL,
   dt_envio         	TIMESTAMP     NOT NULL,
   doc_matricula        VARCHAR(15),
   admmod_matricula     VARCHAR(15),

   PRIMARY KEY  (id)
);

/* Cria tabela Ementa */
CREATE TABLE Ementa(

   id_ementa	INTEGER DEFAULT NEXTVAL('seq_id_ementa') NOT NULL,
   descricao	VARCHAR NOT NULL,

   PRIMARY KEY (id_ementa)

);


/* Cria tabela Fluxograma*/
CREATE TABLE Fluxograma(

   id_fluxograma	    INTEGER	   DEFAULT NEXTVAL('seq_id_fluxograma')   NOT NULL,
   dt_implantacao           TIMESTAMP      NOT NULL	UNIQUE,

   PRIMARY KEY  (id_fluxograma)
);

/* Cria a tabela Foto */
CREATE TABLE  Foto(

  id 		INTEGER       DEFAULT NEXTVAL('seq_id_foto')   NOT NULL,        
  nome          VARCHAR(30)   NOT NULL,
  tipo          VARCHAR(30)   NOT NULL,
  tamanho       VARCHAR(30)   NOT NULL,
  dados         lo,
  id_user       INTEGER       NOT NULL,
  
  PRIMARY KEY  (id)

);

/* Cria tabela GradeCurricular*/
CREATE TABLE GradeCurricular(

   id_grade		    INTEGER	   DEFAULT NEXTVAL('seq_id_grade')   NOT NULL,
   dt_implantacao           TIMESTAMP      NOT NULL	UNIQUE,

   PRIMARY KEY  (id_grade)
);


/* Cria tabela Horario*/
CREATE TABLE Horario(

   dia_semana         VARCHAR(30)    NOT NULL,
   hora_inicio        TIME	     NOT NULL,
   hora_fim           TIME	     NOT NULL,

   PRIMARY KEY  (dia_semana, hora_inicio, hora_fim)
);


/* Cria tabela InfoCurso*/
CREATE TABLE InfoCurso(

   dt_criacao 	            TIMESTAMP     NOT NULL,
   objetivo	            VARCHAR       NOT NULL,
   area_atuacao             VARCHAR       NOT NULL,
   admmod_matricula         VARCHAR(15)   NOT NULL,

   PRIMARY KEY  (dt_criacao)
);


/* Cria tabela Materia*/
CREATE TABLE Materia(

   id_materia	  INTEGER      DEFAULT NEXTVAL('seq_id_materia')    NOT NULL,
   nome           VARCHAR(80)  NOT NULL,

   PRIMARY KEY  (id_materia)
);

/* Cria a tabela Noticia */
CREATE TABLE  Noticia(

  id    	       INTEGER       		DEFAULT NEXTVAL('seq_id_noticia')  NOT NULL,        
  texto                VARCHAR       		NOT NULL,
  assunto              VARCHAR       		NOT NULL,
  situacao             noticia_situacao         DEFAULT 3	NOT NULL,
  tipo_noticia         tp_noticia    		NOT NULL,
  dt_resultado         TIMESTAMP,
  dt_adicionada        TIMESTAMP     		NOT NULL,
  admmod_matricula     VARCHAR(15),
  id_autor             INTEGER       		NOT NULL,
  site		       VARCHAR(50),
  
  PRIMARY KEY  (id)

);

/* Cria tabela ProjetosPesquisa*/
CREATE TABLE ProjetosPesquisa(

   id_projeto	     INTEGER       DEFAULT NEXTVAL('seq_id_projeto')   NOT NULL,
   id_curriculo      INTEGER,
   descricao         VARCHAR       NOT NULL,
   doc_matricula     VARCHAR(15)   NOT NULL,

   PRIMARY KEY  (id_projeto)
);

/* Cria tabela Sala*/
CREATE TABLE Sala(

   id_sala              INTEGER      DEFAULT NEXTVAL('seq_id_sala')	  NOT NULL,
   descricao            VARCHAR(80)    		  NOT NULL,	
   localizacao          VARCHAR(30),
   classificacao        tipo_classificacao        NOT NULL,
  
   PRIMARY KEY  (id_sala)
);

/* Cria tabela SalaDisciplina*/
CREATE TABLE SalaDisciplina(

   id_sala                  INTEGER        DEFAULT NEXTVAL('seq_id_sala') NOT NULL,
   cod_disciplina           VARCHAR(10)    NOT NULL,
   nome                     VARCHAR(30)    NOT NULL,
   turma    	            tipo_turma     NOT NULL,

   PRIMARY KEY  (id_sala, cod_disciplina, nome, turma)
);

/* Cria tabela Sms*/
CREATE TABLE Sms(

   id 	                   INTEGER         DEFAULT NEXTVAL('seq_id_sms')        NOT NULL,
   texto                   VARCHAR(240)    NOT NULL,
   ddd                     VARCHAR(3)      NOT NULL,
   telefone                VARCHAR(8)      NOT NULL,
   assinatura              VARCHAR(10)     NOT NULL,
   dt_envio                TIMESTAMP       NOT NULL,
   doc_matricula           VARCHAR(15),
   admmod_matricula        VARCHAR(15),

   PRIMARY KEY  (id)
);


/* Cria tabela Telefone*/
CREATE TABLE Telefone(

   id               INTEGER             DEFAULT NEXTVAL('seq_id_telefone')   NOT NULL,
   ddd 	            VARCHAR(3)          NOT NULL,
   telefone         VARCHAR(8)          NOT NULL,
   operadora	    tipo_operadora      NOT NULL,
   id_user          INTEGER             NOT NULL,

   PRIMARY KEY  (id)
);

/* Cria tabela TipoUsuario */
CREATE TABLE TipoUsuario(
	
    id_tpuser   INTEGER         NOT NULL,
    tipo	VARCHAR(20)     NOT NULL,

    PRIMARY KEY (id_tpuser)
);

/* Cria a tabela Usuario */
CREATE TABLE  Usuario(

  id_user 	     INTEGER           DEFAULT NEXTVAL('seq_id_user')     NOT NULL,
  cpf                VARCHAR(11)       NOT NULL,
  nome               VARCHAR(15)       NOT NULL,
  sobrenome          VARCHAR(100),
  email              VARCHAR(80)       UNIQUE,
  dt_nasc            TIMESTAMP,
  dt_solicitacao     TIMESTAMP,
  admmod_matricula   VARCHAR(15),                     /* Matrícula do administrador que aprovou o cadastro do usuário no sistema. */
  
  PRIMARY KEY  (id_user)

);
