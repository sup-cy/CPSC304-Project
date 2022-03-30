drop table GameCompany cascade constraint;
drop table Venue cascade constraint;
drop table GameTeam cascade constraint;
drop table Game_DevelopedBy cascade constraint;
drop table GameReferee cascade constraint;
drop table Referees cascade constraint;
drop table EsprotGame_host_playAt_plays cascade constraint;
drop table Player cascade constraint;

create table GameCompany
	(companyName varchar(20),
	founderFn varchar(25) not null,
	founderLn varchar(25) not null,
	hgeadquartersAddress varchar(80),
	headquartersCity varchar(20),
	primary key(companyName));

create table GameTeam
	(teamName varchar(20),
	teamCity varchar(20) not null,
	teamTrophy varchar(200),
	primary key(teamName));

create table Venue
	(venueName varchar(50),
	venueCity varchar(20),
	address varchar(50),
	capacity int,
	primary key (venueName,venueCity));

create table Game_DevelopedBy
	(price number(*,2) not null,
	rating number(2,1),
	companyName varchar(20) not null,
	releaseDate date not null,
	gameName varchar(50) not null,
	primary key (gameName),
	foreign key (companyName) references GameCompany ON DELETE CASCADE);

create table EsprotGame_host_playAt_plays
	(gameName varchar(20) not null,
	homeTeam varchar(20),
	awayTeam varchar(20),
	homescore int,
	awayscore int,
	gamedate date,
	ticketPrice number(*,2),
	venueName varchar(20),
	venueCity varchar(20),
	primary key(gamedate,homeTeam,awayTeam),
	foreign key (gameName) references Game_DevelopedBy
	ON DELETE CASCADE,
	foreign key (venueName, venueCity)references Venue,
	foreign key (homeTeam) references GameTeam ON DELETE CASCADE,
	foreign key (awayTeam) references GameTeam ON DELETE CASCADE);


create table GameReferee
	(refereeID int,
	firstName varchar(20),
	lastName varchar(20),
	workYears int,
	salary number,
	primary key(refereeID)
	);

create table Referees
	(refereeID int,
	gamedate date,
	homeTeam varchar(20),
	awayTeam varchar(20),
	primary key(refereeID,gamedate,homeTeam,awayTeam),
	foreign key (refereeID) references GameReferee,
	foreign key (gamedate,homeTeam,awayTeam) references EsprotGame_host_playAt_plays 
	);



create table Player
	(lastName varchar(20),
	firstName varchar(20),
	gameId int,
	age int,
	position varchar(20),
	salary number,
	totalKill int,
	teamName varchar(20),
	primary key(gameId),
	foreign key (teamName) references GameTeam ON DELETE CASCADE);

insert into GameCompany
values('Riot Games','Brandon','Beck','12333 W Olympic Blvd','Los Angeles');

insert into Game_DevelopedBy
values(0,8.5,'Riot Games',to_date('2009-10-27','YYYY-MM-DD'),'League of Legends');

insert into Venue
values('Laugar dalsholl','Iceland','Engjavegur 8104 Reykjavik',1000);

insert into Venue
values('NiaoChao','BeiJing','1 National Stadium South Road',80000);

insert into GameTeam
values('EDG','ShangHai','2021 League of Legends World Championship');

insert into GameTeam
values('Damwon','FuShan','2021 League of Legends World second Championship');

insert into EsprotGame_host_playAt_plays
values('League of Legends','EDG','Damwon',3,2,to_date('2021-11-06','YYYY-MM-DD'),35.62,'Laugar dalsholl','Iceland');


insert into GameReferee
values(1356,'James','Wang',4,4000);

insert into Referees
values(1356,to_date('2021-11-06','YYYY-MM-DD'),'EDG','Damwon');


insert into Player
values('Ming','Kai',7,29,'JG',20000,1614,'EDG');




