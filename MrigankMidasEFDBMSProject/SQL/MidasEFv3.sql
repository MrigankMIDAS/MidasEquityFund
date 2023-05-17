--15/04/2023, Saturday
/*
Atlas Equity Fund
Apollo Investments
Phoenix Capital Management
Centaur Wealth Management
Helios Fund
Triton Capital Advisors
Janus Capital Group
Thor Equities X
Diana Investments X
Vulcan Capital Management

Apollo [a] is one of the Olympian deities in classical Greek and Roman religion and 
Greek and Roman mythology. The national divinity of the Greeks, Apollo has been 
recognized as a god of archery, music and dance, truth and prophecy, healing and 
diseases, the Sun and light, poetry, and more.
God of beginnings and transitions
Janus was the god of beginnings and transitions in Roman mythology, and presided over
passages, doors, gates and endings, as well as in transitional periods such as from war
to peace.
Vulcan is the god of fire including the fire of volcanoes, deserts, metalworking and 
the forge in ancient Roman religion and myth.
In ancient Greek religion and mythology, Helios is the god and personification of the
Sun. His name is also Latinized as Helius, and he is often given the ...

Odyssey Capital Management
Hercules Investments
Achilles Equity Fund
Spartan Wealth Management
Valiant Capital Group
Athena Investments
Gladiator Fund
Phoenix Wealth Advisors
Argonaut Equity
Theseus Capital Management
*/
CREATE DATABASE midas_EF;
USE midas_EF;
CREATE TABLE employee (
  e_id INT(11) PRIMARY KEY,
  email VARCHAR(320) UNIQUE NOT NULL,
  phno INT(10),
  password VARCHAR(64) NOT NULL,
  first_name VARCHAR(40),
  last_name VARCHAR(40),
  birth_day DATE,--DATE datatype: YYYY-MM-DD
  sex VARCHAR(1),
  salary INT,
  super_id INT--supervisor id is a foreign key which points to another table 
);

ALTER TABLE employee
ADD FOREIGN KEY(super_id)
REFERENCES employee(e_id)
ON DELETE SET NULL; 

USE DATABASE midas_EF;
CREATE TABLE fund(
  f_id INT(11) PRIMARY KEY,
  f_name VARCHAR(40),
  f_fee DECIMAL(9,5),--%
  principal DECIMAL(16,5),
  cash DECIMAL(16,5),
  net_assets DECIMAL(16,5),
  RoR DECIMAL(9,5)--Rate of return in the previous 3 years.
  --%
);

CREATE TABLE stock(
	ticker VARCHAR(5) PRIMARY KEY,
	co_name VARCHAR(50),
	mkt_price DECIMAL(10,5),
	EPS DECIMAL(10,5)
);

CREATE TABLE client(
	c_id INT(11) PRIMARY KEY,
	email VARCHAR(320) UNIQUE NOT NULL,
	phno INT(10),
	password VARCHAR(64) NOT NULL,
	f_name varchar(40),
	l_name varchar(40),
	birth_day DATE,--DATE datatype: YYYY-MM-DD
	sex VARCHAR(1),
	bank_IFSC_code varchar(11) NOT NULL,
	bank_account_no varchar(18)NOT NULL,
	UNIQUE(bank_IFSC_code,bank_account_no)
);

CREATE TABLE employee_works_on_fund(
	e_id INT NOT NULL,
	f_id INT NOT NULL,
	since date NOT NULL,
	FOREIGN KEY(e_id) REFERENCES employee(e_id) ON DELETE CASCADE,
	FOREIGN KEY(f_id) REFERENCES fund(f_id) ON DELETE CASCADE,
	PRIMARY KEY(e_id,f_id)
);

CREATE TABLE invested_in(
	c_id INT(11) NOT NULL,
	f_id INT(11) NOT NULL,
	amount DECIMAL(16,5),
	start_date date,
	end_date date,
	NAV DECIMAL(16,5),
	FOREIGN KEY(c_id) REFERENCES client(c_id) ON DELETE CASCADE,
	FOREIGN KEY(f_id) REFERENCES fund(f_id) ON DELETE CASCADE,
	PRIMARY KEY(c_id,f_id,start_date)
);

CREATE TABLE order_history(
	f_id INT(11) NOT NULL,
	ticker VARCHAR(5) NOT NULL,
	order_size INT(11),--no. of stocks
	--CHECK(no_of_stocks>0),
	order_amount DECIMAL(16,5),--ie., what is the current value of the no. of stocks involved
	tradeType varchar(1)NOT NULL,--BUY or SELL
	order_time DATETIME NOT NULL,
	PRIMARY KEY(f_id,ticker,order_time)
);

ALTER TABLE order_history
ADD CONSTRAINT CHK_order_history CHECK (order_size>0);

CREATE TABLE fund_portfolio(
	f_id INT(11) NOT NULL,
	ticker VARCHAR(5) NOT NULL,
	holding INT(11),--no. of stocks
	value_of_holding DECIMAL(16,5),
	PRIMARY KEY(f_id,ticker)
);
/*
  mgr_id INT,
  mgr_start_date DATE,
  FOREIGN KEY(mgr_id) REFERENCES employee(emp_id)  ON DELETE SET NULL
  --the manager id is a foreign key for branch table which references the employee table's emp id.
  --the meaning of ON DELETE SET NULL will be explained later.
*/
USE midas_EF;
ALTER TABLE client
MODIFY COLUMN  phno INT;
DELETE FROM client;
INSERT INTO client VALUES
(1,'mrigankmarch2002@gmail.com',9599218733,'system','Mrigank','Gupta',
'2002-03-13','m','BKID0010','659921873');


--creating TRIGGERS
USE midas_ef;

DELIMITER //

CREATE TRIGGER invested_inANDfund
	AFTER INSERT ON invested_in
	FOR EACH ROW
	BEGIN
		UPDATE fund SET cash=cash+ NEW.amount WHERE fund.f_id=NEW.f_id;
		UPDATE fund SET principal=principal+ NEW.amount WHERE fund.f_id=NEW.f_id;
		UPDATE fund SET net_assets=net_assets+ NEW.amount WHERE fund.f_id=NEW.f_id;
	END //

DELIMITER ;

USE midas_ef;
DELIMITER $$
CREATE OR REPLACE TRIGGER order_historyANDfund_portfolio
	AFTER INSERT ON order_history
	FOR EACH ROW
	BEGIN
		DECLARE ifexists INT DEFAULT (SELECT count(*) FROM fund_portfolio WHERE 
			fund_portfolio.f_id=NEW.f_id AND fund_portfolio.ticker=NEW.ticker);
		IF (NEW.tradeType='B' AND ifexists=1) THEN 
			UPDATE fund_portfolio SET 
				holding=holding + NEW.order_size,
				value_of_holding=value_of_holding + NEW.order_amount
			WHERE (fund_portfolio.f_id=NEW.f_id AND fund_portfolio.ticker=NEW.ticker);
		ELSEIF (NEW.tradeType='B' AND ifexists=0) THEN
			INSERT INTO fund_portfolio (f_id, ticker, holding, value_of_holding)
			VALUES (NEW.f_id, NEW.ticker, NEW.order_size, NEW.order_amount);
		ELSEIF (NEW.tradeType<>'B' AND ifexists=1) THEN
			UPDATE fund_portfolio SET 
				holding=holding - NEW.order_size,
				value_of_holding=value_of_holding - NEW.order_amount
			WHERE (fund_portfolio.f_id=NEW.f_id AND fund_portfolio.ticker=NEW.ticker);
		END IF;
	END$$
DELIMITER ;

DELIMITER $$
CREATE OR REPLACE TRIGGER order_historyANDfund
	AFTER INSERT ON order_history
	FOR EACH ROW
	BEGIN
		IF (NEW.tradeType='B') THEN 
			UPDATE fund SET 
				cash=cash - NEW.order_amount
			WHERE (fund.f_id=NEW.f_id);
		ELSEIF (NEW.tradeType<>'B') THEN
			UPDATE fund SET 
				cash=cash + NEW.order_amount
			WHERE (fund.f_id=NEW.f_id);
		END IF;
	END$$
DELIMITER ;

CREATE VIEW user_interface_info AS
	SELECT c_id,f_id,f_name,amount,f_fee,RoR,start_date,end_date
	FROM invested_in NATURAL JOIN fund
	ORDER BY start_date,amount;

CREATE VIEW total_amt_of_each_client AS
	SELECT c_id,SUM(amount) FROM invested_in
	GROUP BY c_id;
--Populate DB.

--f_id	f_name	f_fee	principal	cash	net_assets	RoR	
INSERT INTO fund VALUES
(8901,'MIDAS Large Cap',8,0,0,0,29),
(8902,'MIDAS Mid Cap',15,0,0,0,40),
(8903,'Artemis Small Cap',15,0,0,0,45),
(8904,'MIDAS Sensex',1,0,0,0,15),
(8905,'MIDAS BSE 100',5,0,0,0,20);

/*

c_id
email
phno
password
f_name
l_name
birth_day
sex
bank_IFSC_code
bank_account_no
	
1
mrigankmarch2002@gmail.com
2147483647
system
Mrigank
Gupta
2002-03-13
m
BKID0010
659921873
*/
INSERT INTO client VALUES
(2,'arthurdent1990@gmail.com',NULL,'terra01','Arthur','Dent','1990-04-29','M','BKID0012',12435632),
(3, 'bobsmith@yahoo.com', 9876543210, 'password3', 'Bob', 'Smith', '1990-03-03', 'M', 'GHI12345', '234567890123456789'),
(4, 'sallysmith@yahoo.com', NULL, 'password4', 'Sally', 'Smith', '1995-04-04', 'F', 'JKL67890', '345678901234567890'),
(5, 'mikejones@hotmail.com', 5551234567, 'password5', 'Mike', 'Jones', '2000-05-05', 'M', 'MNO12345', '456789012345678901'),
(6, 'katiejones@hotmail.com', NULL, 'password6', 'Katie', 'Jones', '2005-06-06', 'F', 'PQR67890', '567890123456789012'),
(7, 'davidbrown@gmail.com', 1235551212, 'password7', 'David', 'Brown', '2010-07-07', 'M', 'STU12345', '678901234567890123'),
(8, 'ashleybrown@gmail.com', NULL, 'password8', 'Ashley', 'Brown', '2015-08-08', 'F', 'VWX67890', '789012345678901234'),
(9, 'johngreen@yahoo.com', 9875551212, 'password9', 'John', 'Green', '2020-09-09', 'M', 'YZA12345', '890123456789012345'),
(10, 'emilygreen@yahoo.com', NULL, 'password10', 'Emily', 'Green', '2025-10-10', 'F', 'BCD67890', '901234567890123456'),
(11, 'michaelblack@hotmail.com', 5555551212, 'password11', 'Michael', 'Black', '2030-11-11', 'M', 'EFG12345', '012345678901234567'),
(12, 'kellyblack@hotmail.com', NULL, 'password12', 'Kelly', 'Black', '2035-12-12', 'F', 'HIJ67890', '123456789012345678'),
(13, 'stevegray@gmail.com', 1236661212, 'password13', 'Steve', 'Gray', '2040-01-13', 'M', 'KLM12345', '234567890123456789'),
(14, 'sarahgray@gmail.com', NULL, 'password14', 'Sarah', 'Gray', '2045-02-14', 'F', 'NOP67890', '345678901234567890');

/*e_id	email	phno	password	first_name	last_name	birth_day	sex	salary	super_id	
*/
ALTER TABLE employee
MODIFY phno INT(11);
INSERT INTO employee VALUES
(1,'mrigankmarch2002@gmail',9599218733,'system','Mrigank','Gupta','2002-03-13','M',100000,NULL);

INSERT INTO employee (e_id, email, phno, password, first_name, last_name, birth_day, sex, salary, super_id) VALUES
(2, 'john.doe@example.com', 1234567890, 'password123', 'John', 'Doe', '1990-01-01', 'M', 50000, 1),
(3, 'jane.smith@example.com', 1234567891, 'password123', 'Jane', 'Smith', '1991-02-01', 'F', 55000, 1),
(4, 'michael.johnson@example.com', 1234567892, 'password123', 'Michael', 'Johnson', '1985-05-12', 'M', 60000, 2),
(5, 'samantha.williams@example.com', 1234567893, 'password123', 'Samantha', 'Williams', '1988-09-15', 'F', 65000, 2),
(6, 'adam.hernandez@example.com', 1234567894, 'password123', 'Adam', 'Hernandez', '1995-03-27', 'M', 70000, 3),
(7, 'sophie.lee@example.com', 1234567895, 'password123', 'Sophie', 'Lee', '1993-08-08', 'F', 75000, 3),
(8, 'jason.garcia@example.com', 1234567896, 'password123', 'Jason', 'Garcia', '1980-12-30', 'M', 80000, 1),
(9, 'emily.thomas@example.com', 1234567897, 'password123', 'Emily', 'Thomas', '1978-11-07', 'F', 85000, 8);

/*	e_id	f_id	since	*/
INSERT INTO employee_works_on_fund VALUES
(1,8901,'2020-04-16'),
(1,8902,'2020-04-17'),
(1,8903,'2020-04-18'),
(1,8904,'2020-04-19'),
(1,8905,'2020-04-20'),
(2,8901,'2021-01-30'),
(3,8902,'2021-02-25'),
(8,8903,'2021-03-23'),
(8,8903,'2021-04-23'),
(4,8901,'2021-05-21'),
(5,8902,'2021-06-19'),
(6,8903,'2021-07-17'),
(7,8904,'2021-08-15'),
(9,8905,'2021-09-13');

CREATE OR REPLACE VIEW nav AS
SELECT f_id,
CASE WHEN principal=0 THEN NULL
ELSE (net_assets/principal)
END AS navval
FROM fund;


/*c_id	f_id	amount	start_date	end_date	NAV	
*/
INSERT INTO invested_in VALUES
(2,8901,100000,'2023-01-05',NULL,NULL),
(2,8902,80000,'2023-01-05',NULL,NULL),
(3,8903,150000,'2023-01-04',NULL,NULL),
(4,8905,120000,'2023-01-03',NULL,NULL),
(5,8904,120000,'2023-01-04',NULL,NULL),
(6,8901,120000,'2023-01-05',NULL,NULL),
(7,8902,100000,'2023-01-07',NULL,NULL),
(8,8903,80000,'2023-01-05',NULL,NULL),
(9,8904,120000,'2023-01-05',NULL,NULL),
(10,8905,90000,'2023-01-09',NULL,NULL),
(11,8901,100000,'2023-01-04',NULL,NULL),
(12,8902,80000,'2023-01-05',NULL,NULL),
(13,8903,120000,'2023-01-05',NULL,NULL),
(14,8904,80000,'2023-01-08',NULL,NULL),
(14,8903,80000,'2023-01-05',NULL,NULL);
/*1-14, 8901-8905*/


INSERT INTO stock VALUES
INSERT INTO stock (ticker, co_name, mkt_price, EPS) VALUES
('Co01', 'Company 1', 1000, 20.5),
('Co02', 'Company 2', 1500, 31.8),
('Co03', 'Company 3', 850, 19.9),
('Co04', 'Company 4', 2000, 48.3),
('Co05', 'Company 5', 1200, 21.8),
('Co06', 'Company 6', 1800, 50.1),
('Co07', 'Company 7', 905, 19.7),
('Co08', 'Company 8', 1305, 31.4),
('Co09', 'Company 9', 1705, 45.9),
('Co10', 'Company 10', 1100, 21.3),
('Co11', 'Company 11', 900, 11.5),
('Co12', 'Company 12', 250, 36.2),
('Co13', 'Company 13', 700, 11.2),
('Co14', 'Company 14', 1600, 45.0),
('Co15', 'Company 15', 1030, 39.2);


--for selective INSERT
/*
f_id	ticker	order_size	order_amount	tradeType	order_time	

*/

/*
INSERT INTO order_history

INSERT INTO order_history (f_id,ticker,order_size,order_amount,tradeType,order_time)
SELECT 8901,'Co01',20,order_size*(SELECT mkt_price FROM stock WHERE f_id =stock.f_id),'B',CURDATE()
WHERE fund.f_id=f_id AND (fund.cash>=order_amount);


f_id INT(11) NOT NULL,
	ticker VARCHAR(5) NOT NULL,
	order_size INT(11),--no. of stocks
	--CHECK(no_of_stocks>0),
	order_amount DECIMAL(16,5),--ie., what is the current value of the no. of stocks involved
	tradeType varchar(1)NOT NULL,--BUY or SELL
	order_time DATETIME NOT NULL,
	PRIMARY KEY(f_id,ticker,order_time)
	*/
/*
CREATE PROCEDURE place_order
	@f_id_ INT(11),
	@ticker_ VARCHAR(5),
	@order_size_ INT(11),
	@order_Type_ varchar(1),
	@order_time_ DATETIME
AS
BEGIN
	IF (@ticker_ NOT IN (SELECT ticker FROM stock))THEN
	ELSE
		DECLARE order_amount_ DECIMAL(16,5)=@order_size_ *(SELECT mkt_price FROM stock WHERE ticker=@ticker_);
		IF ((@order_Type_='B' AND @order_amount_<=(SELECT cash FROM fund WHERE f_id=@f_id_))
			OR (@order_Type_<>'B' AND @order_size_<=(SELECT holding FROM fund_portfolio WHERE (f_id=@f_id_)AND(token=@token_)))
			INSERT INTO order_history VALUES(@f_id_,@ticker_,@order_size_,@order_Type_,@order_time_));
		END IF;
	END IF;
END*/

/*
CREATE PROCEDURE place_order (
	IN f_id_ INT(11),
	IN ticker_ VARCHAR(5),
	IN order_size_ INT(11),
	IN order_Type_ varchar(1),
	IN order_time_ DATETIME
)
BEGIN
	DECLARE order_amount_ DECIMAL(16,5);
	
	IF NOT EXISTS(SELECT ticker FROM stock WHERE ticker = ticker_) THEN
	ELSE
		SET order_amount_ = order_size_ * (SELECT mkt_price FROM stock WHERE ticker = ticker_);
		IF ((order_Type_ = 'B' AND order_amount_ <= (SELECT cash FROM fund WHERE f_id = f_id_)) OR 
		    (order_Type_ <> 'B' AND order_size_ <= (SELECT holding FROM fund_portfolio WHERE f_id = f_id_ AND token = ticker_))) THEN
			INSERT INTO order_history VALUES(f_id_, ticker_, order_size_, order_Type_, order_time_);
		END IF;
	END IF;
END
*/

INSERT INTO order_history
VALUES(8901,'Co01',20,20000,'B','2023-04-17 12:07:01');

INSERT INTO invested_in VALUES
(1,8901,100000,'2013-01-06',NULL,NULL),
(1,8902,100000,'2023-01-05',NULL,NULL);

USE midas_ef;
CREATE OR REPLACE VIEW manager AS
SELECT e_id,email,password 
FROM employee 
WHERE e_id IN (SELECT super_id FROM employee);

USE midas_ef;
CREATE OR REPLACE VIEW managerportfoliointerface AS
SELECT f_id,ticker,co_name,EPS,holding,mkt_price,holding*mkt_price AS product
FROM fund_portfolio NATURAL JOIN stock;

/*
WITH t1 AS(
    SELECT SUM(product) FROM 
    FROM managerportfoliointerface
    WHERE f_id='{$_SESSION['f_id']}'
)
SELECT ticker,co_name,EPS,holding,mkt_price,product, (product*100/t1) AS Percentage WHERE t1>0;

*/

/*$sql="SELECT ticker,co_name,EPS,holding,mkt_price,product, 
		(product*100/('$row2['su']')) AS cent  FROM managerportfoliointerface 
		WHERE f_id='{$_SESSION['f_id']}';";*/