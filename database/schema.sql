CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE constants (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE access_control (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  module VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE threats (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE reports (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_access (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  module VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE threat_analysis (
  id INT AUTO_INCREMENT,
  threat_id INT NOT NULL,
  analysis TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (threat_id) REFERENCES threats(id)
);

CREATE TABLE report_details (
  id INT AUTO_INCREMENT,
  report_id INT NOT NULL,
  detail TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (report_id) REFERENCES reports(id)
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO constants (name, description) VALUES
('Constant 1', 'This is a constant'),
('Constant 2', 'This is another constant');

INSERT INTO access_control (user_id, module) VALUES
(1, 'ثوابت الأمان'),
(1, 'مراقبة الوصول'),
(1, 'تحليل التهديدات'),
(1, 'التقارير الأمنية');

INSERT INTO threats (name, description) VALUES
('Threat 1', 'This is a threat'),
('Threat 2', 'This is another threat');

INSERT INTO user_access (user_id, module) VALUES
(1, 'ثوابت الأمان'),
(1, 'مراقبة الوصول'),
(1, 'تحليل التهديدات'),
(1, 'التقارير الأمنية');

INSERT INTO reports (name, description) VALUES
('Report 1', 'This is a report'),
('Report 2', 'This is another report');

INSERT INTO threat_analysis (threat_id, analysis) VALUES
(1, 'This is an analysis'),
(2, 'This is another analysis');

INSERT INTO report_details (report_id, detail) VALUES
(1, 'This is a detail'),
(2, 'This is another detail');