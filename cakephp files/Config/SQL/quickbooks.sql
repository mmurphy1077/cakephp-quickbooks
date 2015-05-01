-- 
-- Table structure for table 'quickbooks_user'
-- 

-- Stores usernames and passwords the Web Connector authenticates against

CREATE TABLE quickbooks_user (
  qb_username varchar(40) NOT NULL,                 
  qb_password varchar(40) NOT NULL,              
  qb_company_file varchar(255) default NULL,        
  qbwc_wait_before_next_update int(10) unsigned NOT NULL default '0',
  qbwc_min_run_every_n_seconds int(10) unsigned NOT NULL default '0',
  `status` char(1) NOT NULL,                       
  write_datetime datetime NOT NULL,
  touch_datetime datetime NOT NULL,
  PRIMARY KEY  (qb_username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Table structure for table 'quickbooks_ticket'
-- 

-- Session ticket storage for Web Connector login sessions

CREATE TABLE quickbooks_ticket (
  quickbooks_ticket_id int(10) unsigned NOT NULL auto_increment,
  qb_username varchar(40) NOT NULL,                              
  ticket varchar(32) NOT NULL,                                   
  processed int(10) unsigned NOT NULL default '0',               
  lasterror_num varchar(16) NOT NULL default '0',               
  lasterror_msg varchar(255) default NULL,                       
  ipaddr varchar(15) NOT NULL,                                  
  write_datetime datetime NOT NULL,                             
  touch_datetime datetime NOT NULL,                              
  PRIMARY KEY  (quickbooks_ticket_id),
  KEY qb_username (qb_username)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- 
-- Table structure for table 'quickbooks_config'
-- 

CREATE TABLE quickbooks_config (
  quickbooks_config_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  qb_username varchar(40) NOT NULL,
  module varchar(40) NOT NULL,
  cfgkey varchar(40) NOT NULL,
  cfgval varchar(40) NOT NULL,
  cfgtype varchar(40) NOT NULL,
  cfgopts text NOT NULL,
  write_datetime datetime NOT NULL,
  mod_datetime datetime NOT NULL,
  PRIMARY KEY (quickbooks_config_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Table structure for table 'quickbooks_connection'
--

CREATE TABLE quickbooks_connection (
  quickbooks_connection_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  qb_username varchar(40) NOT NULL,
  certificate varchar(255) DEFAULT NULL,
  application_id int(10) unsigned NOT NULL,
  application_login varchar(40) DEFAULT NULL,
  lasterror_num varchar(32) DEFAULT NULL,
  lasterror_msg varchar(255) DEFAULT NULL,
  connection_ticket varchar(255) DEFAULT NULL,
  connection_datetime datetime NOT NULL,
  write_datetime datetime NOT NULL,
  touch_datetime datetime NOT NULL,
  PRIMARY KEY (quickbooks_connection_id)
)

-- 
-- Table structure for table 'quickbooks_ident'
-- 

CREATE TABLE quickbooks_ident (
  quickbooks_ident_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  qb_username varchar(40) NOT NULL,               
  qb_object varchar(40) NOT NULL,                  
  unique_id varchar(40) NOT NULL,                 
  qb_ident varchar(40) NOT NULL,                   
  editsequence varchar(40) NOT NULL,              
  extra text,
  map_datetime datetime NOT NULL,                  
  PRIMARY KEY (quickbooks_ident_id)
) 

-- 
-- Table structure for table 'quickbooks_log'
--

CREATE TABLE quickbooks_log (
  quickbooks_log_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  quickbooks_ticket_id int(10) unsigned DEFAULT NULL,           
  batch int(10) unsigned NOT NULL,                              
  msg text NOT NULL,                                            
  log_datetime datetime NOT NULL,                              
  PRIMARY KEY (quickbooks_log_id),
  KEY quickbooks_ticket_id (quickbooks_ticket_id),
  KEY batch (batch)
)

-- 
-- Table structure for table 'quickbooks_notify'
--

CREATE TABLE quickbooks_notify (
  quickbooks_notify_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  qb_username varchar(40) NOT NULL,
  qb_object varchar(40) NOT NULL,
  unique_id varchar(40) NOT NULL,
  qb_ident varchar(40) NOT NULL,
  errnum int(10) unsigned DEFAULT NULL,
  errmsg text NOT NULL,
  note text NOT NULL,
  priority int(10) unsigned NOT NULL,
  write_datetime datetime NOT NULL,
  mod_datetime datetime NOT NULL,
  PRIMARY KEY (quickbooks_notify_id)
) 

-- 
-- Table structure for table 'quickbooks_queue'
--

CREATE TABLE quickbooks_queue (
  quickbooks_queue_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  quickbooks_ticket_id int(10) unsigned DEFAULT NULL,          
  qb_username varchar(40) NOT NULL,                             
  qb_action varchar(32) NOT NULL,                              
  ident varchar(40) NOT NULL,                                   
  extra text,                                                   
  qbxml text,                                                    
  priority int(10) unsigned DEFAULT '0',                        
  qb_status char(1) NOT NULL,                                   
  msg text,                                                    
  enqueue_datetime datetime NOT NULL,                          
  dequeue_datetime datetime DEFAULT NULL,                       
  PRIMARY KEY (quickbooks_queue_id),
  KEY quickbooks_ticket_id (quickbooks_ticket_id),
  KEY priority (priority),
  KEY qb_username (qb_username,qb_action,ident,qb_status),
  KEY qb_status (qb_status)
)

CREATE TABLE quickbooks_recur (
  quickbooks_recur_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  qb_username varchar(40) NOT NULL,
  qb_action varchar(32) NOT NULL,
  ident varchar(40) NOT NULL,
  extra text,
  qbxml text,
  priority int(10) unsigned DEFAULT '0',
  run_every int(10) unsigned NOT NULL,
  recur_lasttime int(10) unsigned NOT NULL,
  enqueue_datetime datetime NOT NULL,
  PRIMARY KEY (quickbooks_recur_id),
  KEY qb_username (qb_username,qb_action,ident),
  KEY priority (priority)
) 