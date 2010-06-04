/* Desenvolvido em 16 de maio de 2010 - Drop dos gatilhos do CICCOL */

/* Drop dos gatilhos */

DROP TRIGGER  tg_VerificaDataNasc      		ON Usuario;

DROP TRIGGER tg_AtualizaDataResultado  		ON Noticia;

DROP TRIGGER tg_FuncInsereSemestre		ON Aluno;

/* Drop das funções chamadas pelos gatilhos */
DROP FUNCTION FuncDataNascimento()		CASCADE;

DROP FUNCTION FuncDataResultado()		CASCADE;

DROP FUNCTION FuncInsereSemestre()		CASCADE;




