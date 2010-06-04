/* Desenvolvido em 16 de maio de 2010 - Criação das visões do CICCOL */

/* Visão para exibir as disciplinas ministradas pelo professor */
CREATE VIEW ViewDocenteDisciplina(id_user, doc_nome, doc_sobrenome, cod_disciplina, disc_nome, disc_turma) AS
SELECT D.id_user, D.nome, D.sobrenome, Di.cod_disciplina, Di.nome, Di.turma FROM (Docente NATURAL JOIN Usuario) D  JOIN Disciplina Di
ON D.matricula = Di.doc_matricula;

/* Visão para exibir as disciplinas cursadas pelo aluno */
CREATE VIEW ViewAlunoDisciplina(id_user, alu_nome, alu_sobrenome, alu_disciplina, disc_nome, disc_turma) AS
SELECT A.id_user, A.nome, A.sobrenome, Di.cod_disciplina, Di.nome, Di.turma FROM (Aluno NATURAL JOIN Usuario) A JOIN Disciplina Di
ON A.matricula = Di.doc_matricula;
 