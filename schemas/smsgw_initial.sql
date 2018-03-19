/*


INBOUND
{
  "messageId": "0A0000000123ABCD1",
  "msisdn": "447700900001",
  "to": "447700900000",
  "text": "Hello world",
  "type": "text",
  "keyword": "Hello",
  "message-timestamp": "2020-01-01T12:00:00.000+00:00",
  "timestamp": "1578787200",
  "nonce": "aaaaaaaa-bbbb-cccc-dddd-0123456789ab",
  "concat": "true",
  "concat-ref": "1",
  "concat-total": "3",
  "concat-part": "2",
  "data": "abc123",
  "udh": "abc123"
}

DLVR
{
  "messageId": "0A0000001234567B",
  "msisdn": "447700900000",
  "to": "Acme Inc",
  "message-timestamp": "2020-01-01T12:00:00.000+00:00"
  "network-code": "12345",
  "price": "0.03330000",
  "status": "delivered",  <-- delivered, expired, failed, rejected, accepted, buffered, unknown
  "scts": "2001011400",
  "err-code": "0",
}


*/


DROP TABLE IF EXISTS `tivoli2018_smsgw`;
CREATE TABLE `tivoli2018_smsgw` (
  `id` INT(6) NOT NULL AUTO_INCREMENT,
  `msisdn` INT(16) NOT NULL,
  `to` INT(16) NOT NULL,
  `direction` ENUM('0','1') DEFAULT '0',
  `text` TEXT,
  `type` ENUM('text','unicode','binary') DEFAULT 'text',
  `keyword` varchar(20),
  `smsgw-messageId` varchar(16),
  `message-timestamp` varchar(20),
  `timestamp` INT(10),
  `concat` ENUM('true','false'),
  `concat-ref` INT(3),
  `concat-total` INT(2),
  `price` VARCHAR(10),
  `remaining-balance` VARCHAR(10),
  `status` VARCHAR(10),
  `scts` INT(10),
  `err-code` INT(2),
  `data` VARCHAR(180) DEFAULT NULL,
  `udh` VARCHAR(180) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tivoli2018_smsgw`
  ADD KEY `msisdn_key` (`msisdn`),
  ADD KEY `to_key` (`to`),
  ADD KEY `message-timestamp_key` (`message-timestamp`),
  ADD KEY `created_at_key` (`created_at`);

desc tivoli2018_smsgw