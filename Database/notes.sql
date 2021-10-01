
-- Create Table notes
CREATE TABLE root.notes  (
   id number NOT NULL,
   value float NOT NULL,
   examName  varchar(200) NOT NULL,
   FK_subject number NOT NULL,
   FK_user number NOT NULL,
   FK_semester number DEFAULT NULL
);
GRANT SELECT, UPDATE, INSERT, DELETE ON root.notes TO usr;
--
-- Daten für Tabelle  notes
--

INSERT ALL
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (1, 5.1, 'Prüfung 1', 4, 1, 1)
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (2, 5.2, 'Prüfung 2', 4, 1, 1)
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (3, 2.8, 'Algebra 1', 6, 1, 1)
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (4, 3.12, 'Brüche', 6, 1, 1)
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (5, 2.59, 'Lineare Gleichungen', 6, 1, 1)
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (6, 5.1, 'Prüfung 1', 1, 1, 1)
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (7, 4.4, 'Prüfung 2', 1, 1, 1)
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (8, 5.9, 'Prüfung 3', 1, 1, 1)
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (9, 3.3, 'Prüfung 1', 2, 1, 1 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (10, 3.4, 'Prüfung 2', 2, 1, 1 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (11, 3.75, 'Mündlichprüfung', 2, 1, 1 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (12, 2.3, 'Voc 1', 3, 1, 1 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (13, 3.85, 'Voc 2', 3, 1, 1 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (14, 3, 'Voc 3', 3, 1, 1 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (15, 3, 'Voc 4', 3, 1, 1 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (16, 4, 'Online Prüfung 1', 5, 1, 1 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (17, 5, 'Französische Revolution + Napoleon', 4, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (18, 4.37, 'Lineare Gleichungssysteme', 6, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (19, 5.2, 'Prüfung 4', 1, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (20, 4.9, 'Prüfung 5', 1, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (21, 5.4, 'Datenmodell implementieren', 7, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (22, 5.3, 'Codierungs-, Kompressions- und Verschlüsselungsverfahren einsetzen', 8, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (27, 4.2, 'LB1', 16, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (28, 4.2, 'LB2', 16, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (29, 5, 'LB1', 23, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (30, 4.2, 'LB2-T1', 23, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (31, 3.7, 'LB2-T2', 23, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (32, 5.1, 'LB2-T3', 23, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (33, 6, 'LB2-T4', 23, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (34, 4.8, 'LB2', 23, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (35, 5.1, 'LB3', 23, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (36, 5, 'Sozialkompetenzen', 23, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (37, 5.5, 'Sozialkompetenzen', 16, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (38, 4.8, 'Prüfung 1', 39, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (39, 4.7, 'Prüfung 2', 39, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (40, 5.2, 'Prüfung 3', 39, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (41, 5.5, 'Kompetenzraster', 43, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (42, 6, 'Abschluss', 11, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (43, 5.5, 'Abschluss', 17, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (44, 5.5, 'Abschluss', 18, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (45, 1.3, 'Französisch Voci 1', 3, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (46, 1.9, 'Französisch Prüfung 1', 2, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (47, 4.25, 'Französisch IDAF', 2, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (48, 4.6, 'BWL Online Prüfung Bewerbungen', 5, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (49, 2, 'Voci 2', 3, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (50, 3.5, 'Quadratische Gleichungen', 6, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (51, 2.7, 'Französisch 3/4', 2, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (52, 2.6, 'Voci 3', 3, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (53, 5.2, 'LB3', 7, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (54, 5.8, 'LB2', 7, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (55, 6, 'LB2', 8, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (56, 5.1, 'LB3', 8, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (57, 6, 'Abschlussprüfung 105', 20, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (59, 4.7, 'Prüfung 2 (CH und Industrialisierung)', 4, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (60, 3.2, 'Prüfung 6', 1, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (61, 4, 'Prüfung 3', 5, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (62, 6, 'Uek-307 Abschlussnote', 54, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (63, 3.8, 'Trigonometrie', 6, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (64, 2, 'Prüfung', 2, 1, 2 )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (68, 5.3, 'Prüfung 1', 40, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (69, 5.1, 'LB1', 26, 1, NULL )
INTO root.notes ( id ,  value ,  examName ,  FK_subject ,  FK_user ,  FK_semester ) VALUES (70, 4.6, 'LB2', 26, 1, NULL )
SELECT 1 FROM DUAL;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle  schools
--

CREATE TABLE root.schools  (
   id number NOT NULL,
   schoolName  varchar(255) NOT NULL
);
GRANT SELECT, UPDATE, INSERT, DELETE ON root.schools TO usr;

--
-- Daten für Tabelle  schools
--

INSERT ALL
INTO  root.schools  ( id ,  schoolName ) VALUES (1, 'BMS')
INTO  root.schools  ( id ,  schoolName ) VALUES (2, 'LAP')
SELECT 1 FROM DUAL;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle  semesters
--

CREATE TABLE root.semesters  (
   id number NOT NULL,
   semesterTag  varchar(50) NOT NULL
);
GRANT SELECT, UPDATE, INSERT, DELETE ON root.semesters TO usr;

--
-- Daten für Tabelle  semesters
--

INSERT ALL
INTO  root.semesters  ( id ,  semesterTag ) VALUES (0, 'BMS Abschlussnoten')
INTO  root.semesters  ( id ,  semesterTag ) VALUES (1, 'Sommer 2020 - Winter 2021')
INTO  root.semesters  ( id ,  semesterTag ) VALUES (2, 'Winter 2021 - Sommer 2021')
INTO  root.semesters  ( id ,  semesterTag ) VALUES (12, 'Sommer 2021 - Winter 2022')
SELECT 1 FROM DUAL;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle  session_links
--

CREATE TABLE root.session_links  (
   id number NOT NULL,
   FK_user number NOT NULL,
   link  varchar(255)   NOT NULL,
   token  number NOT NULL,
   create_date  date  DEFAULT sysdate NOT NULL
);
GRANT SELECT, UPDATE, INSERT, DELETE ON root.session_links TO usr;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle  stickynotes
--

CREATE TABLE root.stickynotes  (
   PK_stickynote number NOT NULL,
   createtime  date DEFAULT sysdate NOT NULL,
   title  varchar(300) DEFAULT 'Neue Notiz' NOT NULL,
   value varchar(2500) NOT NULL,
   FK_user number NOT NULL
);
GRANT SELECT, UPDATE, INSERT, DELETE ON root.stickynotes TO usr;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle  subjects
--

CREATE TABLE root.subjects (
   id number NOT NULL,
   subjectName  varchar(255)   NOT NULL,
   FK_school number NOT NULL,
   additionalTag  varchar(200) DEFAULT NULL,
   FK_overSubject number DEFAULT NULL
);
GRANT SELECT, UPDATE, INSERT, DELETE ON root.subjects TO usr;

--
-- Daten für Tabelle  subjects
--

INSERT ALL
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (1, 'Chemie', 1, NULL, NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (2, 'Französisch', 1, NULL, NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (3, 'Französisch Vokabeln', 1, NULL, 2 )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (4, 'Geschichte', 1, NULL, NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (5, 'BWL', 1, NULL, NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (6, 'Mathematik', 1, NULL, NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (7, 'M104', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (8, 'M114', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (11, 'M304', 2, 'ÜK Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (12, 'Resultat der Arbeit', 2, 'IPA Abschlussprüfung', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (13, 'Dokumentation', 2, 'IPA Abschlussprüfung', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (15, 'Fachgespräch und Präsentation', 2, 'IPA Abschlussprüfung', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (16, 'M100', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (17, 'M305', 2, 'ÜK Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (18, 'M101', 2, 'ÜK Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (19, 'M318', 2, 'ÜK Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (20, 'M105', 2, 'ÜK Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (21, 'M107', 2, 'ÜK Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (22, 'M335', 2, 'ÜK Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (23, 'M117', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (24, 'M120', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (25, 'M122', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (26, 'M123', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (27, 'M133', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (28, 'M150', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (29, 'M151', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (30, 'M152', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (31, 'M153', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (32, 'M183', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (33, 'M214', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (34, 'M226', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (35, 'M242', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (36, 'M254', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (37, 'M306', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (38, 'M326', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (39, 'M403', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (40, 'M404', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (41, 'M411', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (42, 'M426', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (43, 'M431', 2, 'Berufsfachschule Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (54, 'M307', 2, 'ÜK Module', NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (57, 'Naturwissenschaften', 1, NULL, NULL )
INTO  root.subjects ( id ,  subjectName ,  FK_school ,  additionalTag ,  FK_overSubject ) VALUES (60, 'Physik', 1, NULL, NULL)
SELECT 1 FROM DUAL;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle  users
--

CREATE TABLE root.users  (
   id number NOT NULL,
   username  varchar(100)   NOT NULL,
   email  varchar(255)   NOT NULL,
   email_confirmed number(1) DEFAULT 1 NOT NULL,
   passwordhash  varchar(1000) NOT NULL,
   profilepicture  varchar(100)  DEFAULT 'defaultpb.jpg' NOT NULL,
   admin number(1) DEFAULT 0 NOT NULL
);
GRANT SELECT, UPDATE, INSERT, DELETE ON root.users TO usr;

--
-- Daten für Tabelle  users
--

INSERT INTO root.users  ( id ,  username ,  email ,  email_confirmed ,  passwordhash ,  profilepicture ,  admin ) VALUES (1, 'Florian Gubler', 'gubler.florian@gmx.net', 0, '7527334b5de227445adccbf52e9671178e310e3c8f41a6bda7399fa9aecfd8b6', 'profilepicture_9.jpg', 1 );

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle  notes
--
ALTER TABLE root.notes
  ADD PRIMARY KEY ( id  );

--
-- Indizes für die Tabelle  schools
--
ALTER TABLE root.schools
  ADD PRIMARY KEY ( id );

--
-- Indizes für die Tabelle  semesters
--
ALTER TABLE root.semesters
  ADD PRIMARY KEY ( id );

--
-- Indizes für die Tabelle  session_links
--
ALTER TABLE root.session_links
  ADD PRIMARY KEY ( id  )

--
-- Indizes für die Tabelle  stickynotes
--
ALTER TABLE root.stickynotes
  ADD PRIMARY KEY ( PK_stickynote );

--
-- Indizes für die Tabelle  subjects
--
ALTER TABLE root.subjects
  ADD PRIMARY KEY ( id  );

--
-- Indizes für die Tabelle  users
--
ALTER TABLE root.users
  ADD PRIMARY KEY ( id );

--
-- Constraints der Tabelle  notes
--
ALTER TABLE root.notes
  ADD CONSTRAINT  notes_ibfk_1  FOREIGN KEY ( FK_subject ) REFERENCES   root.subjects ( id  )
  ADD CONSTRAINT  notes_ibfk_2  FOREIGN KEY ( FK_subject ) REFERENCES   root.subjects ( id  )
  ADD CONSTRAINT  notes_ibfk_3  FOREIGN KEY ( FK_user ) REFERENCES  root.users  ( id  )
  ADD CONSTRAINT  notes_ibfk_4  FOREIGN KEY ( FK_semester ) REFERENCES  root.semesters  ( id  )
  ADD CONSTRAINT  notes_ibfk_5  FOREIGN KEY ( FK_user ) REFERENCES  root.users  ( id  )
  ADD CONSTRAINT  notes_ibfk_6  FOREIGN KEY ( FK_user ) REFERENCES  root.users  ( id  )
  ADD CONSTRAINT  notes_ibfk_7  FOREIGN KEY ( FK_user ) REFERENCES  root.users  ( id  )
  ADD CONSTRAINT  notes_ibfk_8  FOREIGN KEY ( FK_user ) REFERENCES  root.users  ( id );

--
-- Constraints der Tabelle  session_links
--
ALTER TABLE  root.session_links
  ADD CONSTRAINT session_links  FOREIGN KEY ( FK_user ) REFERENCES  root.users  ( id );

--
-- Constraints der Tabelle  subjects
--
ALTER TABLE root.subjects
  ADD CONSTRAINT  subjects_ibfk_1  FOREIGN KEY ( FK_school ) REFERENCES  root.schools  ( id  )
  ADD CONSTRAINT  subjects_ibfk_2  FOREIGN KEY ( FK_overSubject ) REFERENCES   root.subjects ( id );


COMMIT;