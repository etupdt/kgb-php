
CREATE DATABASE IF NOT EXISTS kgb;

USE kgb;

CREATE TABLE IF NOT EXISTS person(
  id int auto_increment  NOT NULL,
  firstName varchar (100) NOT NULL,
  lastName varchar (100) NOT NULL,
	CONSTRAINT person_PK PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS administrator(
  id_person int NOT NULL,
  id int NOT NULL,
  email varchar (255) NOT NULL,
  password varchar (100) NOT NULL,
  insertAt date NOT NULL,
  firstName varchar (100) NOT NULL,
  lastName varchar (100) NOT NULL,
	CONSTRAINT administrator_PK PRIMARY KEY (id_person, id),
	CONSTRAINT administrator_person_FK FOREIGN KEY (id_person) REFERENCES person(id)
);

CREATE TABLE IF NOT EXISTS country(
  id int auto_increment NOT NULL,
  name varchar (50) NOT NULL,
  nationality varchar (50) NOT NULL,
	CONSTRAINT country_PK PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS actor(
  id_person int NOT NULL,
  id int NOT NULL,
  birthdate date NOT NULL,
  identificationCode int,
  id_country int NOT NULL,
	CONSTRAINT actor_PK PRIMARY KEY (id),
  CONSTRAINT actor_person_FK FOREIGN KEY (id_person) REFERENCES person(id),
	CONSTRAINT actor_country_FK FOREIGN KEY (id_country) REFERENCES country(id)
);

CREATE TABLE IF NOT EXISTS typemission(
  id int auto_increment NOT NULL,
  typeMission varchar (50) NOT NULL,
	CONSTRAINT typemission_PK PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS statut(
  id int auto_increment NOT NULL,
  statut varchar (20) NOT NULL,
	CONSTRAINT statut_PK PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS speciality(
  id int auto_increment NOT NULL,
  name varchar (50) NOT NULL,
	CONSTRAINT statut_PK PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS hideout(
  id int auto_increment NOT NULL,
  code varchar (10) NOT NULL,
  address varchar (200) NOT NULL,
  type varchar (50) NOT NULL,
  id_country int NOT NULL,
	CONSTRAINT hideout_PK PRIMARY KEY (id),
  CONSTRAINT hideout_country_FK FOREIGN KEY (id_country) REFERENCES country(id)
);

CREATE TABLE IF NOT EXISTS role(
  id int auto_increment NOT NULL,
  role varchar (20) NOT NULL,
	CONSTRAINT role_PK PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS mission(
  id int auto_increment NOT NULL,
  title varchar (100) NOT NULL,
  description text NOT NULL,
  codeName varchar (100) NOT NULL,
  begin date NOT NULL,
  end date NOT NULL,
  id_typemission int NOT NULL,
  id_statut int NOT NULL,
  id_country int NOT NULL,
  id_speciality int NOT NULL,
	CONSTRAINT mission_PK PRIMARY KEY (id),
  CONSTRAINT mission_typemission_FK FOREIGN KEY (id_typemission) REFERENCES typemission(id),
  CONSTRAINT mission_statut_FK FOREIGN KEY (id_statut) REFERENCES statut(id),
	CONSTRAINT mission_country_FK FOREIGN KEY (id_country) REFERENCES country(id),
	CONSTRAINT mission_speciality_FK FOREIGN KEY (id_speciality) REFERENCES speciality(id)
);

CREATE TABLE IF NOT EXISTS mission_hideout(
  id_mission int NOT NULL ,
  id_hideout int NOT NULL,
	CONSTRAINT mission_hideout_PK PRIMARY KEY (id_mission, id_hideout),
  CONSTRAINT mission_hideout_mission_FK FOREIGN KEY (id_mission) REFERENCES mission(id),
	CONSTRAINT mission_hideout_hideout_FK FOREIGN KEY (id_hideout) REFERENCES hideout(id)
);

CREATE TABLE IF NOT EXISTS actor_mission(
  id_actor int NOT NULL,
  id_mission int NOT NULL ,
	CONSTRAINT actor_mission_PK PRIMARY KEY (id_actor, id_mission),
	CONSTRAINT actor_mission_actor_FK FOREIGN KEY (id_actor) REFERENCES actor(id),
  CONSTRAINT actor_mission_mission_FK FOREIGN KEY (id_mission) REFERENCES mission(id)
);

CREATE TABLE IF NOT EXISTS actor_speciality(
  id_actor int NOT NULL,
  id_speciality int NOT NULL ,
	CONSTRAINT actor_speciality_PK PRIMARY KEY (id_actor, id_speciality),
	CONSTRAINT actor_speciality_actor_FK FOREIGN KEY (id_actor) REFERENCES actor(id),
  CONSTRAINT actor_speciality_speciality_FK FOREIGN KEY (id_speciality) REFERENCES speciality(id)
);

CREATE TABLE IF NOT EXISTS mission_actor_role(
  id_mission int NOT NULL,
  id_actor int NOT NULL,
  id_role int NOT NULL ,
	CONSTRAINT mission_actor_role_PK PRIMARY KEY (id_mission, id_actor, id_role),
	CONSTRAINT mission_actor_role_mission_FK FOREIGN KEY (id_mission) REFERENCES mission(id),
	CONSTRAINT mission_actor_role_actor_FK FOREIGN KEY (id_actor) REFERENCES actor(id),
  CONSTRAINT mission_actor_role_role_FK FOREIGN KEY (id_role) REFERENCES role(id)
);
