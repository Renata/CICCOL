/* Desenvolvido em 13 de maio de 2010 - Defini��o dos dom�nios dos atributos do CICCOL */

-- Defini��o do dom�nio lo para armazenar imagem (tabela Banner e Foto). Esse dom�nio � do tipo bytea.
CREATE DOMAIN lo as BYTEA; 

-- Defini��o do dom�nio do tipo de operadora (tabela Telefone), onde 1-VIVO, 2-OI, 3-TIM, 4-CLARO
CREATE DOMAIN tipo_operadora AS SMALLINT CHECK (VALUE IN(1, 2, 3, 4));

-- Defini��o do dom�nio da situa��o do aluno (tabela Aluno), onde 1-Cursando e 2-Matr�cula Trancada
CREATE DOMAIN tipo_situacao AS SMALLINT CHECK (VALUE IN(1, 2));

-- Defini��o do dom�nio da turma (tabela Disciplina e Associadas), onde 1-Turma A, 2-Turma B e 3-Turma �nica
CREATE DOMAIN tipo_turma AS SMALLINT CHECK (VALUE IN(1, 2, 3));

-- Defini��o do dom�nio da classifica��o da sala (tabela Sala), onde 1-Sala de Aula, 2-Sala de Professor 
CREATE DOMAIN tipo_classificacao AS SMALLINT CHECK (VALUE IN(1, 2));

-- Defini��o do dom�nio da situa��o da not�cia em rela��o a publica��o (tabela Not�cia), onde 1-Aprovada, 2-Reprovada e 3-Pendente 
CREATE DOMAIN noticia_situacao AS SMALLINT CHECK (VALUE IN(1, 2, 3));

-- Defini��o do dom�nio do tipo de not�cia (tabela Not�cia), onde 1-Emprego, 2-Edital e 3-Reportagem 
CREATE DOMAIN tp_noticia AS SMALLINT CHECK (VALUE IN(1, 2, 3));

-- Defini��o do dom�nio do semestre de entrada do aluno (tabela Aluno), onde 1-Primeiro Semestre do ano, 2-Segundo Semestre do ano 
CREATE DOMAIN semest_entrada AS SMALLINT CHECK (VALUE IN(1, 2));

-- Defini��o do dom�nio que indica se a disciplina � optativa ou n�o, onde 1-n�o-optativa e 2-optativa
CREATE DOMAIN opt_disciplina AS SMALLINT CHECK (VALUE IN(1, 2));


