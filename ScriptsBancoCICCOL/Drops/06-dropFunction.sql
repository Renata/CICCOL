/* Desenvolvido em 16 de maio de 2010 - Drop das funções do CICCOL */


/* Drop das funções */

DROP FUNCTION  FuncAprovaNoticia(INT)       					     	 CASCADE;

DROP FUNCTION  FuncSolicitacoesCadastro()    					     	 CASCADE;

DROP FUNCTION  VerificaSolicitacaoCadastSis(VARCHAR(15), VARCHAR(70))    		 CASCADE;

DROP FUNCTION  FuncReprovaNoticia(INT)       					     	 CASCADE;

DROP FUNCTION  FuncRecupSenha(VARCHAR(15))                                               CASCADE;

DROP FUNCTION  FuncAlterarSenha(VARCHAR(15), VARCHAR(50), VARCHAR(50), VARCHAR(50))      CASCADE;

DROP FUNCTION  FuncListaDocDisciplina(INT)					     	 CASCADE;

DROP FUNCTION FuncListaAlunoDisciplina(INT)						 CASCADE;

DROP FUNCTION FuncFormataData(TIMESTAMP)				            	 CASCADE;

DROP FUNCTION calcIdade(TIMESTAMP)							 CASCADE;


/* Drop dos tipos criados para as funções */

DROP TYPE solicitacoes   		CASCADE;
DROP TYPE noticiaResultado		CASCADE;
DROP TYPE docente_disciplina	        CASCADE;
DROP TYPE aluno_disciplina	        CASCADE;

