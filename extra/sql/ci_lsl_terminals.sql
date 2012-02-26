CREATE TABLE IF NOT EXISTS `ci_lsl_terminals` (
  `uuid` varchar(36) NOT NULL,
  `url` varchar(256) NOT NULL,
  `name` varchar(50) NOT NULL,
  `region` varchar(50) NOT NULL,
  `parcel` varchar(50) NOT NULL,
  `position` varchar(32) NOT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
