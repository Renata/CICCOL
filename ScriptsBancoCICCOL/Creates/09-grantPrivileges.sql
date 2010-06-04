/* Desenvolvido em 16 de maio de 2010 - Atribuição dos privilégios aos grupos de usuários do CICCOL */


GRANT USAGE ON LANGUAGE plpgsql TO GROUP administrador, moderador, docente, aluno;

/* Atribuição dos privilégios aos grupos administrador e moderador */

GRANT ALL PRIVILEGES ON AdminModerador    		TO GROUP administrador;
GRANT ALL PRIVILEGES ON Aluno				TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON AlunoAreaInteresse	        TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON AlunoDisciplina			TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON AreaInteresse			TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON AreaInteresseCurriculo		TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON Autenticacao		        TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON Autor				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Banner				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON CalendarioEventos		TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON CalendarioSemestre		TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON Cargo				TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON Contato				TO GROUP administrador, moderador;
GRANT SELECT ON Curriculo				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Disciplina		        TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON DisciplinaHorario		TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON DisciplinaEmail			TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON DisciplinaFluxograma		TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON DisciplinaGradeCurricular	TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON DisciplinaRequisitaDisciplina	TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON DisciplinaSms			TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON Docente				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Email				TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON Ementa				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Fluxograma			TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON Foto				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON GradeCurricular			TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Horario				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON InfoCurso			TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Materia				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Noticia				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON ProjetosPesquisa		TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Sala				TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON SalaDisciplina			TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON Sms				TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Telefone			TO GROUP administrador, moderador;
GRANT ALL PRIVILEGES ON TipoUsuario			TO GROUP administrador, moderador;

GRANT ALL PRIVILEGES ON Usuario				TO GROUP administrador, moderador;

GRANT SELECT, UPDATE ON AdminModerador    		TO GROUP moderador;

/* Atribuição dos privilégios aos grupos docente e aluno*/

GRANT ALL PRIVILEGES ON AreaInteresseCurriculo	TO GROUP docente;

GRANT ALL PRIVILEGES ON Curriculo	        TO GROUP docente;

GRANT UPDATE, INSERT ON Docente			TO GROUP docente;

GRANT INSERT ON Email			        TO GROUP docente;

GRANT ALL PRIVILEGES ON Foto			TO GROUP docente, aluno;

GRANT INSERT ON Noticia			        TO GROUP docente, aluno;

GRANT ALL PRIVILEGES ON ProjetosPesquisa        TO GROUP docente;

GRANT INSERT ON Sms			        TO GROUP docente;

GRANT ALL PRIVILEGES ON Telefone		TO GROUP docente, aluno;

GRANT UPDATE, INSERT ON Usuario			TO GROUP docente, aluno;

GRANT UPDATE, INSERT ON Aluno	        	TO GROUP aluno;
