/* Desenvolvido em 16 de maio de 2010 - Remoção dos privilégios aos grupos de usuários do CICCOL */

REVOKE ALL ON LANGUAGE plpgsql FROM administrador, moderador, docente, aluno;

/* Remoção dos privilégios dos grupos administrador e moderador */

REVOKE ALL PRIVILEGES ON AdminModerador    		FROM GROUP administrador;
REVOKE ALL PRIVILEGES ON Aluno				FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON AlunoAreaInteresse	        FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON AlunoDisciplina		FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON AreaInteresse			FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON AreaInteresseCurriculo		FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON Autenticacao		        FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON Autor				FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Banner				FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON CalendarioEventos		FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON CalendarioSemestre		FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON Cargo				FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON Contato			FROM GROUP administrador, moderador;
REVOKE SELECT ON Curriculo				FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Disciplina		        FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON DisciplinaHorario		FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON DisciplinaEmail		FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON DisciplinaFluxograma		FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON DisciplinaGradeCurricular	FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON DisciplinaRequisitaDisciplina	FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON DisciplinaSms			FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON Docente			FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Email				FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON Ementa				FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Fluxograma			FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON Foto				FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON GradeCurricular		FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Horario			FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON InfoCurso			FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Materia			FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Noticia			FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON ProjetosPesquisa		FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Sala				FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON SalaDisciplina			FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON Sms				FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Telefone			FROM GROUP administrador, moderador;
REVOKE ALL PRIVILEGES ON TipoUsuario			FROM GROUP administrador, moderador;

REVOKE ALL PRIVILEGES ON Usuario			FROM GROUP administrador, moderador;

REVOKE SELECT, UPDATE ON AdminModerador    		FROM GROUP moderador;

/* Remoção dos privilégios dos grupos docente e aluno*/

REVOKE ALL PRIVILEGES ON AreaInteresseCurriculo	FROM GROUP docente;

REVOKE ALL PRIVILEGES ON Curriculo	        FROM GROUP docente;

REVOKE UPDATE, INSERT ON Docente		FROM GROUP docente;

REVOKE INSERT ON Email			        FROM GROUP docente;

REVOKE ALL PRIVILEGES ON Foto			FROM GROUP docente, aluno;

REVOKE INSERT ON Noticia			FROM GROUP docente, aluno;

REVOKE ALL PRIVILEGES ON ProjetosPesquisa        FROM GROUP docente;

REVOKE INSERT ON Sms			        FROM GROUP docente;

REVOKE ALL PRIVILEGES ON Telefone		FROM GROUP docente, aluno;

REVOKE UPDATE, INSERT ON Usuario		FROM GROUP docente, aluno;

REVOKE UPDATE, INSERT ON Aluno	        	FROM GROUP aluno;
