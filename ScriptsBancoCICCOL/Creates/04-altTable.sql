/* Desenvolvido em 13 de maio de 2010 - Atribuição das FOREIGN KEY as entidades do CICCOL */


/* Especifica as chaves estrangeiras dos relacionamentos 1:N e 1:1 */
ALTER TABLE AdminModerador               	ADD FOREIGN KEY (id_user)        		REFERENCES Usuario(id_user)	        ON UPDATE CASCADE;
ALTER TABLE Aluno                        	ADD FOREIGN KEY (id_user)        		REFERENCES Usuario(id_user)             ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE Autenticacao                 	ADD FOREIGN KEY (id_user)            		REFERENCES Usuario(id_user)             ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE Autenticacao                      	ADD FOREIGN KEY (id_tpuser)               	REFERENCES TipoUsuario(id_tpuser)       ON UPDATE CASCADE;

ALTER TABLE Banner                       	ADD FOREIGN KEY (admmod_matricula)        	REFERENCES AdminModerador(matricula)    ON UPDATE CASCADE;

ALTER TABLE CalendarioEventos            	ADD FOREIGN KEY (admmod_matricula)        	REFERENCES AdminModerador(matricula)    ON UPDATE CASCADE;
ALTER TABLE CalendarioSemestre           	ADD FOREIGN KEY (admmod_matricula)        	REFERENCES AdminModerador(matricula)    ON UPDATE CASCADE;
ALTER TABLE Contato                      	ADD FOREIGN KEY (admmod_matricula)        	REFERENCES AdminModerador(matricula)    ON UPDATE CASCADE;
ALTER TABLE Curriculo                    	ADD FOREIGN KEY (doc_matricula)        		REFERENCES Docente(matricula)           ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE Curriculo                    	ADD FOREIGN KEY (id_cargo_atual)    		REFERENCES Cargo(id_cargo)              ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE Docente                      	ADD FOREIGN KEY (id_sala)              		REFERENCES Sala(id_sala)        	ON DELETE SET NULL    ON UPDATE CASCADE;
ALTER TABLE Docente                      	ADD FOREIGN KEY (id_user)              		REFERENCES Usuario(id_user)     	ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE Disciplina                   	ADD FOREIGN KEY (doc_matricula)        		REFERENCES Docente(matricula)   	ON DELETE SET NULL    ON UPDATE CASCADE;
ALTER TABLE Disciplina                   	ADD FOREIGN KEY (id_materia)       		REFERENCES Materia(id_materia)  	ON DELETE SET NULL    ON UPDATE CASCADE;
ALTER TABLE Disciplina                   	ADD FOREIGN KEY (id_ementa)       		REFERENCES Ementa(id_ementa)  	        ON DELETE SET NULL    ON UPDATE CASCADE;

ALTER TABLE Email                        	ADD FOREIGN KEY (doc_matricula)          	REFERENCES Docente(matricula)           ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE Email                        	ADD FOREIGN KEY (admmod_matricula)          	REFERENCES AdminModerador(matricula)    ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE Foto                         	ADD FOREIGN KEY (id_user)        		REFERENCES Usuario(id_user)             ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE InfoCurso                       	ADD FOREIGN KEY (admmod_matricula)        	REFERENCES AdminModerador(matricula)    ON UPDATE CASCADE;

ALTER TABLE Noticia                      	ADD FOREIGN KEY (id_autor)         		REFERENCES Autor(id_autor)              ON UPDATE CASCADE;
ALTER TABLE Noticia                      	ADD FOREIGN KEY (admmod_matricula)        	REFERENCES AdminModerador(matricula)    ON UPDATE CASCADE;

ALTER TABLE ProjetosPesquisa             	ADD FOREIGN KEY (id_curriculo)        		REFERENCES Curriculo(id_curriculo)      ON DELETE SET NULL    ON UPDATE CASCADE;
ALTER TABLE ProjetosPesquisa                    ADD FOREIGN KEY (doc_matricula)        		REFERENCES Docente(matricula)           ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE Sms                          	ADD FOREIGN KEY (doc_matricula)         	REFERENCES Docente(matricula)           ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE Sms                          	ADD FOREIGN KEY (admmod_matricula)          	REFERENCES AdminModerador(matricula)    ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE Telefone                     	ADD FOREIGN KEY (id_user)        		REFERENCES Usuario(id_user)             ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE Usuario                     	ADD FOREIGN KEY (admmod_matricula)          	REFERENCES AdminModerador(matricula)    ON UPDATE CASCADE;


/* Foreign Key de tabelas obtidas de relacionamentos N:N */


ALTER TABLE AlunoAreaInteresse           	ADD FOREIGN KEY (alu_matricula)       						   REFERENCES Aluno(matricula)					ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE AlunoAreaInteresse           	ADD FOREIGN KEY (id_interesse)        						   REFERENCES AreaInteresse(id_interesse)			ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE AlunoDisciplina              	ADD FOREIGN KEY (alu_matricula)        						   REFERENCES Aluno(matricula)					ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE AlunoDisciplina              	ADD FOREIGN KEY (cod_disciplina, nome, turma)        				   REFERENCES Disciplina(cod_disciplina, nome, turma)		ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE AreaInteresseCurriculo       	ADD FOREIGN KEY (id_interesse)        						   REFERENCES AreaInteresse(id_interesse)			ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE AreaInteresseCurriculo       	ADD FOREIGN KEY (id_curriculo)        						   REFERENCES Curriculo(id_curriculo)				ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE DisciplinaHorario            	ADD FOREIGN KEY (cod_disciplina, nome, turma)        				   REFERENCES Disciplina(cod_disciplina, nome, turma)		ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE DisciplinaHorario            	ADD FOREIGN KEY (dia_semana, hora_inicio, hora_fim)        			   REFERENCES Horario(dia_semana, hora_inicio, hora_fim)	ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE DisciplinaEmail              	ADD FOREIGN KEY (cod_disciplina, nome, turma)          				   REFERENCES Disciplina(cod_disciplina, nome, turma)		ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE DisciplinaEmail              	ADD FOREIGN KEY (id)        							   REFERENCES Email(id)						ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE DisciplinaFluxograma         	ADD FOREIGN KEY (cod_disciplina, nome, turma)        				   REFERENCES Disciplina(cod_disciplina, nome, turma)		ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE DisciplinaFluxograma         	ADD FOREIGN KEY (id_fluxograma)        					           REFERENCES Fluxograma(id_fluxograma)			        ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE DisciplinaGradeCurricular   	ADD FOREIGN KEY (cod_disciplina, nome, turma)        				   REFERENCES Disciplina(cod_disciplina, nome, turma)		ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE DisciplinaGradeCurricular    	ADD FOREIGN KEY (id_grade)        						   REFERENCES GradeCurricular(id_grade)				ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE DisciplinaRequisitaDisciplina	ADD FOREIGN KEY (cod_disciplina, nome, turma)        				   REFERENCES Disciplina(cod_disciplina, nome, turma)		ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE DisciplinaRequisitaDisciplina	ADD FOREIGN KEY (requisito_cod_disciplina, requisito_nome, requisito_turma)        REFERENCES Disciplina(cod_disciplina, nome, turma)		ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE DisciplinaSms             	        ADD FOREIGN KEY (cod_disciplina, nome, turma)        				   REFERENCES Disciplina(cod_disciplina, nome, turma)		ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE DisciplinaSms              	        ADD FOREIGN KEY (id)        							   REFERENCES Sms(id)						ON DELETE CASCADE     ON UPDATE CASCADE;

ALTER TABLE SalaDisciplina            	        ADD FOREIGN KEY (cod_disciplina, nome, turma)           			   REFERENCES Disciplina(cod_disciplina, nome, turma)		ON DELETE CASCADE     ON UPDATE CASCADE;
ALTER TABLE SalaDisciplina             	        ADD FOREIGN KEY (id_sala)      							   REFERENCES Sala(id_sala)					ON DELETE CASCADE     ON UPDATE CASCADE;
