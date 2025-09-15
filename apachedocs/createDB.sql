PROMPT 'CREATE @C:/apachedocs/createDB.sql;'

DROP TABLE products CASCADE CONSTRAINTS;
CREATE TABLE products(
	prds_id       	INTEGER NOT NULL
	,prds_stat 		VARCHAR2(32) NOT NULL
	,prds_name     	VARCHAR2(32) NOT NULL
	,prds_tp_type		VARCHAR2(32) NOT NULL
	,prds_empl_id		INTEGER NULL
	,prds_docs_id		INTEGER NULL
	,prds_dfct_id		INTEGER NULL
	,prds_eqpt_id		INTEGER NULL
);
DROP TABLE employees CASCADE CONSTRAINTS;
CREATE TABLE employees (
	empl_id         INTEGER NOT NULL
	,empl_adr      	VARCHAR2(32) NULL
	,empl_job       VARCHAR2(32) NULL
	,empl_secn     VARCHAR2(32) NULL
	,empl_surn    	VARCHAR2(32) NOT NULL
	,empl_name      VARCHAR2(32) NOT NULL
	,empl_pass      VARCHAR2(32) NOT NULL
);

DROP TABLE equipment CASCADE CONSTRAINTS;
CREATE TABLE equipment (
	eqpt_id			INTEGER NOT NULL
	,eqpt_date		DATE NULL
	,eqpt_type		VARCHAR2(32) NULL
	,eqpt_desc		VARCHAR2(32) NULL
	,eqpt_name     VARCHAR2(32) NOT NULL
);

DROP TABLE defects CASCADE CONSTRAINTS;
CREATE TABLE defects (
	dfct_id 		INTEGER NOT NULL
	,dfct_date		DATE NULL
	,dfct_type		VARCHAR2(32) NULL
	,dfct_desc		VARCHAR2(32) NULL
	,dfct_name		VARCHAR2(32) NOT NULL
);

DROP TABLE components CASCADE CONSTRAINTS;
CREATE TABLE components (
	comp_id         INTEGER NOT NULL
	,comp_date		DATE NULL
	,comp_type		VARCHAR2(32) NULL
	,comp_name		VARCHAR2(32) NOT NULL
);

DROP TABLE documents CASCADE CONSTRAINTS;
CREATE TABLE documents (
	docs_id		INTEGER NOT NULL
	,docs_auth		VARCHAR2(20) NOT NULL
	,docs_type		VARCHAR2(32) NULL
	,docs_date		DATE NOT NULL
	,docs_name		VARCHAR2(20) NOT NULL
);

CREATE UNIQUE INDEX i_prds_id ON products (prds_id);
ALTER TABLE products
      ADD  ( CONSTRAINT pk_prds_id PRIMARY KEY (prds_id) ) ;
	  
CREATE UNIQUE INDEX i_empl_id ON employees (empl_id);
ALTER TABLE employees
      ADD  (CONSTRAINT pk_empl_id PRIMARY KEY (empl_id) ) ;
	  
CREATE UNIQUE INDEX i_eqpt_id ON equipment (eqpt_id);
ALTER TABLE equipment
      ADD  (CONSTRAINT pk_eqpt_id PRIMARY KEY (eqpt_id) ) ;
	  
CREATE UNIQUE INDEX i_dfct_id ON defects (dfct_id);
ALTER TABLE defects
      ADD  (CONSTRAINT pk_dfct_id PRIMARY KEY (dfct_id) ) ;

CREATE UNIQUE INDEX i_comp_id ON components (comp_id);
ALTER TABLE components
      ADD  (CONSTRAINT pk_comp_id PRIMARY KEY (comp_id) ) ;

CREATE UNIQUE INDEX i_docs_id ON documents (docs_id);
ALTER TABLE documents
      ADD  (CONSTRAINT pk_docs_id PRIMARY KEY (docs_id) ) ;

ALTER TABLE products
      ADD  (CONSTRAINT c_prds_eqpt_id FOREIGN KEY (prds_eqpt_id)
            REFERENCES equipment);

ALTER TABLE products
      ADD  (CONSTRAINT c_prds_empl_id FOREIGN KEY (prds_empl_id)
            REFERENCES employees);

ALTER TABLE products
      ADD  (CONSTRAINT c_prds_docs_id FOREIGN KEY (prds_docs_id)
            REFERENCES documents);
		
ALTER TABLE products
      ADD  (CONSTRAINT c_prds_dfct_id FOREIGN KEY (prds_dfct_id)
            REFERENCES defects);

DROP SEQUENCE s_prds_id;
DROP SEQUENCE s_empl_id;
DROP SEQUENCE s_eqpt_id;
DROP SEQUENCE s_dfct_id;
DROP SEQUENCE s_comp_id;
DROP SEQUENCE s_docs_id;
CREATE SEQUENCE s_prds_id START WITH 1;
CREATE SEQUENCE s_empl_id START WITH 1;
CREATE SEQUENCE s_eqpt_id START WITH 1;
CREATE SEQUENCE s_dfct_id START WITH 1;
CREATE SEQUENCE s_comp_id START WITH 1;
CREATE SEQUENCE s_docs_id START WITH 1;

CREATE OR REPLACE TRIGGER tr_prds_id
BEFORE INSERT ON products FOR EACH ROW
BEGIN
      SELECT s_prds_id.NEXTVAL
      INTO :new.prds_id
      FROM DUAL;
END;
/
CREATE OR REPLACE TRIGGER tr_empl_id
BEFORE INSERT ON employees FOR EACH ROW
BEGIN
      SELECT s_empl_id.NEXTVAL
      INTO :new.empl_id
      FROM DUAL;
END;
/
CREATE OR REPLACE TRIGGER tr_eqpt_id
BEFORE INSERT ON equipment FOR EACH ROW
BEGIN
      SELECT s_eqpt_id.NEXTVAL
      INTO :new.eqpt_id
      FROM DUAL;
END;
/
CREATE OR REPLACE TRIGGER tr_dfct_id
BEFORE INSERT ON defects FOR EACH ROW
BEGIN
      SELECT s_dfct_id.NEXTVAL
      INTO :new.dfct_id
      FROM DUAL;
END;
/
CREATE OR REPLACE TRIGGER tr_docs_id
BEFORE INSERT ON documents FOR EACH ROW
BEGIN
      SELECT s_docs_id.NEXTVAL
      INTO :new.docs_id
      FROM DUAL;
END;
/
CREATE OR REPLACE TRIGGER tr_comp_id
BEFORE INSERT ON components FOR EACH ROW
BEGIN
      SELECT s_comp_id.NEXTVAL
      INTO :new.comp_id
      FROM DUAL;
END;
/

INSERT INTO products (prds_id, prds_stat, prds_name, prds_tp_type, prds_eqpt_id, prds_empl_id, prds_docs_id, prds_dfct_id)
VALUES (1, 'Active', 'Product A', 'test type', 1, 1, 1, 1);
INSERT INTO employees (empl_id, empl_adr, empl_job, empl_secn, empl_surn, empl_name, empl_pass)
VALUES (1, 'Address 1', 'Engineer', 'Section 1', 'Surname 1', 'Name 1', '123123');
INSERT INTO equipment (eqpt_id, eqpt_date, eqpt_type, eqpt_desc, eqpt_name)
VALUES (1, (SELECT SYSDATE FROM DUAL), 'Type 1', 'Description 1', 'Equipment 1');
INSERT INTO defects (dfct_id, dfct_date, dfct_type, dfct_desc, dfct_name)
VALUES (1, (SELECT SYSDATE FROM DUAL), 'Type 1', 'Description 1', 'Defect 1');
INSERT INTO components (comp_id, comp_date, comp_type, comp_name)
VALUES (1, (SELECT SYSDATE FROM DUAL), 'Type 1', 'Component 1');
INSERT INTO documents (docs_id, docs_auth, docs_type, docs_date, docs_name)
VALUES (1, 'Author 1', 'Type 1', (SELECT SYSDATE FROM DUAL), 'Document 1');

COMMIT;