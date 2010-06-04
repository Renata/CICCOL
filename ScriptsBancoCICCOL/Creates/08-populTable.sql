/* Desenvolvido em 13 de maio de 2010 - Povoamento do Banco CICCOL */

/* Povoamento da tabela TipoUsuario(id_tpuser, tipo) */
INSERT INTO TipoUsuario(id_tpuser, tipo) VALUES (1, 'Administrador');
INSERT INTO TipoUsuario(id_tpuser, tipo) VALUES (2, 'Moderador');
INSERT INTO TipoUsuario(id_tpuser, tipo) VALUES (3, 'Docente');
INSERT INTO TipoUsuario(id_tpuser, tipo) VALUES (4, 'Aluno');

/* Usuário Padrão */
INSERT INTO Usuario(cpf, nome) VALUES ('root', 'root');
INSERT INTO Autenticacao(identificador, senha, id_user, id_tpuser) VALUES ('root', '1234', 1, 1);

/* Povoamento da tabela Usuario(cpf, id_user, nome, sobrenome, email, dt_nasc, dt_solicitacao) */
INSERT INTO Usuario(cpf, nome, sobrenome, email, dt_nasc, dt_solicitacao) VALUES ('34526789123', 'Michele', 'Amaral Brandão', 'micheleerafick@gmail.com', '05-may-1989', '05-may-2010');
INSERT INTO Usuario(cpf, nome, sobrenome, email, dt_nasc, dt_solicitacao) VALUES ('89789876543', 'Renata', 'Cruz Marins', 'remarins15@gmail.com', '12-jan-1987', '05-may-2010');
INSERT INTO Usuario(cpf, nome, sobrenome, email, dt_nasc, dt_solicitacao) VALUES ('90875423156', 'Marcelo', 'Honda', 'mhonda@gmail.com', '22-jun-1975', '05-may-2010');
INSERT INTO Usuario(cpf, nome, sobrenome, email, dt_nasc, dt_solicitacao) VALUES ('78965478098', 'Francisco', 'Bruno', 'brunoso@gmail.com', '06-feb-1970', '05-may-2010');
INSERT INTO Usuario(cpf, nome, sobrenome, email, dt_nasc, dt_solicitacao) VALUES ('32456789564', 'Leonardo', 'Ciríaco', 'leo@gmail.com', '28-may-1986', '05-may-2010');

/* Povoamento da tabela Aluno(matricula, semestre, semestre_entrada, ano_entrada, situacao, id_user) */
INSERT INTO Aluno(matricula, semestre_entrada, ano_entrada, situacao, id_user) VALUES ('200710487', 1, 2010, 1, 6);
  

/* Povoamento da tabela AdminModerador(matricula, id_user) */
INSERT INTO AdminModerador(matricula, id_user) VALUES ('200710488', 2);
INSERT INTO AdminModerador(matricula, id_user) VALUES ('200710503', 3);

/* Povoamento da tabela Docente(matricula, id_sala, id_user) */
INSERT INTO Docente(matricula, id_sala, id_user) VALUES ('12310488', NULL, 4);
INSERT INTO Docente(matricula, id_sala, id_user) VALUES ('45315488', NULL, 5);

/* Povoamento da tabela Autor(id_autor, nome, email) */
INSERT INTO Autor(nome, email) VALUES ('Fulano de Tal', 'fulanodetal@email.com');
INSERT INTO Autor(nome, email) VALUES ('Sicrano de Tal', 'sicranodetal@email.com');

/* Povoamento da tabela Noticia(id, texto, assunto, situacao, tipo_noticia, dt_resultado, dt_adicionada, admmod_matricula, id_autor) */
INSERT INTO Noticia(texto, assunto, situacao, tipo_noticia, dt_resultado, dt_adicionada, admmod_matricula, id_autor) VALUES ('Olá...isso é um teste!!', 'Teste', 1, 2, '04-mar-2010', '01-mar-2010', '200710488', 1);
INSERT INTO Noticia(texto, assunto, tipo_noticia, dt_resultado, dt_adicionada, admmod_matricula, id_autor) VALUES ('Olá...isso é um segundo teste!!', 'Teste 2', 2, '12-mar-2010', '01-mar-2010', '200710488', 2);


/* Povoamento da tabela Autenticacao(identificador, senha, dt_acesso, id_user, id_tpuser); */
INSERT INTO Autenticacao VALUES ('12310488', '123', '01-mar-2010', 4, 3);

/* Povoamento da tabela Foto(id, nome, tipo, tamanho, dados, id_user) */
INSERT INTO Foto(nome, tipo, tamanho, dados, id_user) VALUES ('imagem', 'jpg', '512x512', NULL, 4);

/* Povoamento da tabela Cargo(id_cargo, descricao) */
INSERT INTO Cargo(descricao) VALUES ('Professor adjunto');

/* Povoamento da tabela Curriculo(id_curriculo, ultimo_emprego, perfil_profissional, doc_matricula, id_cargo_atual) */
INSERT INTO Curriculo(ultimo_emprego, perfil_profissional, doc_matricula, id_cargo_atual) VALUES ('professor da UEFS', 'Atuo na área de pesquisa de computação paralela em multicore', '12310488', 1);

/* Povoamento da tabela Email(id, texto, assunto, remetente, destinatario, dt_envio, doc_matricula, admmod_matricula) */
INSERT INTO Email(texto, assunto, remetente, destinatario, dt_envio, doc_matricula, admmod_matricula) VALUES ('Olá...isso é um teste', 'teste', 'testes@gmail.com', 'destino@gmail.com', '23-mar-2010', '12310488', NULL);

/* Povoamento da tabela Sms(id, texto, ddd, telefone, assinatura, dt_envio, doc_matricula, admmod_matricula) */
INSERT INTO Sms(texto, ddd, telefone, assinatura, dt_envio, doc_matricula, admmod_matricula) VALUES ('Olá...isso é um teste', '073', '88075436', 'test', '23-mar-2010', '12310488', NULL);

/* Povoamento da tabela Telefone(id, ddd, telefone, operadora, id_user) */
INSERT INTO Telefone(ddd, telefone, operadora, id_user) VALUES ('073', '88075436', 1, 4);

/* Povoamento da tabela Materia(nome) */
INSERT INTO Materia(nome) VALUES ('Análise de Algoritmos');
INSERT INTO Materia(nome) VALUES ('Teoria da Computação');

/* Povoamento da tabela Disciplina(cod_disciplina, nome, turma, carga_horaria, qtd_max_aluno, num_cred, semestre, doc_matricula, id_materia)*/
INSERT INTO Disciplina(cod_disciplina, nome, turma, carga_horaria, qtd_max_aluno, num_cred, semestre, doc_matricula, id_materia) VALUES ('CET023', 'Linguagem de Programação I', 3, 60, 30, 4, 1, '12310488', 1);
INSERT INTO Disciplina(cod_disciplina, nome, turma, carga_horaria, qtd_max_aluno, num_cred, semestre, doc_matricula, id_materia) VALUES ('CET025', 'CLP', 3, 60, 30, 4, 3, '45315488', 2);
INSERT INTO Disciplina(cod_disciplina, nome, turma, carga_horaria, qtd_max_aluno, num_cred, semestre, doc_matricula, id_materia) VALUES ('CET203', 'Lógica Digital I', 3, 60, 30, 4, 4, NULL, 2);

/* Povoamento dos Requisitos das Disciplinas */

INSERT INTO DisciplinaRequisitaDisciplina(cod_disciplina, nome, turma, requisito_cod_disciplina, requisito_nome, requisito_turma) VALUES ('CET023', 'Linguagem de Programação I', 3, 'CET203', 'Lógica Digital I', 3);
INSERT INTO DisciplinaRequisitaDisciplina(cod_disciplina, nome, turma, requisito_cod_disciplina, requisito_nome, requisito_turma) VALUES ('CET023', 'Linguagem de Programação I', 3, 'CET025', 'CLP', 3);
