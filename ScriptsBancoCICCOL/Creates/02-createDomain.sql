/* Desenvolvido em 13 de maio de 2010 - Definição dos domínios dos atributos do CICCOL */

-- Definição do domínio lo para armazenar imagem (tabela Banner e Foto). Esse domínio é do tipo bytea.
CREATE DOMAIN lo as BYTEA; 

-- Definição do domínio do tipo de operadora (tabela Telefone), onde 1-VIVO, 2-OI, 3-TIM, 4-CLARO
CREATE DOMAIN tipo_operadora AS SMALLINT CHECK (VALUE IN(1, 2, 3, 4));

-- Definição do domínio da situação do aluno (tabela Aluno), onde 1-Cursando e 2-Matrícula Trancada
CREATE DOMAIN tipo_situacao AS SMALLINT CHECK (VALUE IN(1, 2));

-- Definição do domínio da turma (tabela Disciplina e Associadas), onde 1-Turma A, 2-Turma B e 3-Turma Única
CREATE DOMAIN tipo_turma AS SMALLINT CHECK (VALUE IN(1, 2, 3));

-- Definição do domínio da classificação da sala (tabela Sala), onde 1-Sala de Aula, 2-Sala de Professor 
CREATE DOMAIN tipo_classificacao AS SMALLINT CHECK (VALUE IN(1, 2));

-- Definição do domínio da situação da notícia em relação a publicação (tabela Notícia), onde 1-Aprovada, 2-Reprovada e 3-Pendente 
CREATE DOMAIN noticia_situacao AS SMALLINT CHECK (VALUE IN(1, 2, 3));

-- Definição do domínio do tipo de notícia (tabela Notícia), onde 1-Emprego, 2-Edital e 3-Reportagem 
CREATE DOMAIN tp_noticia AS SMALLINT CHECK (VALUE IN(1, 2, 3));

-- Definição do domínio do semestre de entrada do aluno (tabela Aluno), onde 1-Primeiro Semestre do ano, 2-Segundo Semestre do ano 
CREATE DOMAIN semest_entrada AS SMALLINT CHECK (VALUE IN(1, 2));

-- Definição do domínio que indica se a disciplina é optativa ou não, onde 1-não-optativa e 2-optativa
CREATE DOMAIN opt_disciplina AS SMALLINT CHECK (VALUE IN(1, 2));


