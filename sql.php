<?php
 $sql="CREATE TABLE crypto(
`id`                      INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
`date`                    VARCHAR(20)   NOT NULL DEFAULT '' ,
`hour`                    VARCHAR(20)   NOT NULL DEFAULT '' ,
`TYPE`                    VARCHAR (20)  NOT NULL DEFAULT '' ,
`MARKET`                  VARCHAR (20)  NOT NULL DEFAULT '' ,
`FROMSYMBOL`              VARCHAR (20)  NOT NULL DEFAULT '' ,
`TOSYMBOL`                VARCHAR (20)  NOT NULL DEFAULT '' ,
`FLAGS`                   VARCHAR (20)  NOT NULL DEFAULT '' ,
`PRICE`                   DOUBLE        NOT NULL DEFAULT '0',
`LASTUPDATE`              DOUBLE        NOT NULL DEFAULT '0',
`LASTVOLUME`              DOUBLE        NOT NULL DEFAULT '0',
`LASTVOLUMETO`            DOUBLE        NOT NULL DEFAULT '0',
`LASTTRADEID`             VARCHAR (200) NOT NULL DEFAULT '',
`VOLUMEDAY`               DOUBLE        NOT NULL DEFAULT '0',
`VOLUMEDAYTO`             DOUBLE        NOT NULL DEFAULT '0',
`VOLUME24HOUR`            DOUBLE        NOT NULL DEFAULT '0',
`VOLUME24HOURTO`          DOUBLE        NOT NULL DEFAULT '0',
`OPENDAY`                 DOUBLE        NOT NULL DEFAULT '0',
`HIGHDAY`                 DOUBLE        NOT NULL DEFAULT '0',
`LOWDAY`                  DOUBLE        NOT NULL DEFAULT '0',
`OPEN24HOUR`              DOUBLE        NOT NULL DEFAULT '0',
`HIGH24HOUR`              DOUBLE        NOT NULL DEFAULT '0',
`LOW24HOUR`               DOUBLE        NOT NULL DEFAULT '0',
`VOLUMEHOUR`              DOUBLE        NOT NULL DEFAULT '0',
`VOLUMEHOURTO`            DOUBLE        NOT NULL DEFAULT '0',
`OPENHOUR`                DOUBLE        NOT NULL DEFAULT '0',
`HIGHHOUR`                DOUBLE        NOT NULL DEFAULT '0',
`LOWHOUR`                 DOUBLE        NOT NULL DEFAULT '0',
`CHANGE24HOUR`            DOUBLE        NOT NULL DEFAULT '0',
`CHANGEPCT24HOUR`         DOUBLE        NOT NULL DEFAULT '0',
`CHANGEDAY`               DOUBLE        NOT NULL DEFAULT '0',
`CHANGEPCTDAY`            DOUBLE        NOT NULL DEFAULT '0',
`SUPPLY`                  DOUBLE        NOT NULL DEFAULT '0',
`MKTCAP`                  DOUBLE        NOT NULL DEFAULT '0',
`TOTALVOLUME24H`          DOUBLE        NOT NULL DEFAULT '0',
`TOTALVOLUME24HTO`        DOUBLE        NOT NULL DEFAULT '0',
`TOTALTOPTIERVOLUME24H`   DOUBLE        NOT NULL DEFAULT '0',
`TOTALTOPTIERVOLUME24HTO` DOUBLE        NOT NULL DEFAULT '0',
`IMAGEURL`                VARCHAR (200) NOT NULL DEFAULT ''
)";


CREATE TABLE crypto(
    `id`                      INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `date`                    VARCHAR(20)   NOT NULL DEFAULT '' ,
    `hour`                    VARCHAR(20)   NOT NULL DEFAULT '' ,
    `TYPE`                    VARCHAR (20)  NOT NULL DEFAULT '' ,
    `MARKET`                  VARCHAR (20)  NOT NULL DEFAULT '' ,
    `FROMSYMBOL`              VARCHAR (20)  NOT NULL DEFAULT '' ,
    `TOSYMBOL`                VARCHAR (20)  NOT NULL DEFAULT '' ,
    `FLAGS`                   VARCHAR (20)  NOT NULL DEFAULT '' ,
    `PRICE`                   DOUBLE        NOT NULL DEFAULT '0',
    `LASTUPDATE`              DOUBLE        NOT NULL DEFAULT '0',
    `LASTVOLUME`              DOUBLE        NOT NULL DEFAULT '0',
    `LASTVOLUMETO`            DOUBLE        NOT NULL DEFAULT '0',
    `LASTTRADEID`             VARCHAR (200) NOT NULL DEFAULT '',
    `VOLUMEDAY`               DOUBLE        NOT NULL DEFAULT '0',
    `VOLUMEDAYTO`             DOUBLE        NOT NULL DEFAULT '0',
    `VOLUME24HOUR`            DOUBLE        NOT NULL DEFAULT '0',
    `VOLUME24HOURTO`          DOUBLE        NOT NULL DEFAULT '0',
    `OPENDAY`                 DOUBLE        NOT NULL DEFAULT '0',
    `HIGHDAY`                 DOUBLE        NOT NULL DEFAULT '0',
    `LOWDAY`                  DOUBLE        NOT NULL DEFAULT '0',
    `OPEN24HOUR`              DOUBLE        NOT NULL DEFAULT '0',
    `HIGH24HOUR`              DOUBLE        NOT NULL DEFAULT '0',
    `LOW24HOUR`               DOUBLE        NOT NULL DEFAULT '0',
    `VOLUMEHOUR`              DOUBLE        NOT NULL DEFAULT '0',
    `VOLUMEHOURTO`            DOUBLE        NOT NULL DEFAULT '0',
    `OPENHOUR`                DOUBLE        NOT NULL DEFAULT '0',
    `HIGHHOUR`                DOUBLE        NOT NULL DEFAULT '0',
    `LOWHOUR`                 DOUBLE        NOT NULL DEFAULT '0',
    `CHANGE24HOUR`            DOUBLE        NOT NULL DEFAULT '0',
    `CHANGEPCT24HOUR`         DOUBLE        NOT NULL DEFAULT '0',
    `CHANGEDAY`               DOUBLE        NOT NULL DEFAULT '0',
    `CHANGEPCTDAY`            DOUBLE        NOT NULL DEFAULT '0',
    `SUPPLY`                  DOUBLE        NOT NULL DEFAULT '0',
    `MKTCAP`                  DOUBLE        NOT NULL DEFAULT '0',
    `TOTALVOLUME24H`          DOUBLE        NOT NULL DEFAULT '0',
    `TOTALVOLUME24HTO`        DOUBLE        NOT NULL DEFAULT '0',
    `TOTALTOPTIERVOLUME24H`   DOUBLE        NOT NULL DEFAULT '0',
    `TOTALTOPTIERVOLUME24HTO` DOUBLE        NOT NULL DEFAULT '0',
    `IMAGEURL`                VARCHAR (200) NOT NULL DEFAULT ''
    )

    CREATE TABLE exchange(
        `id`                      INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `date`                    VARCHAR(20)   NOT NULL DEFAULT '' ,
        `hour`                    VARCHAR(20)   NOT NULL DEFAULT '' ,
        `exchangeId`              VARCHAR(20)   NOT NULL DEFAULT '' ,
        `name`                    VARCHAR(20)   NOT NULL DEFAULT '' ,
        `rank`                    INT(16)       NOT NULL DEFAULT '0',
        `percentTotalVolume`      DOUBLE        NOT NULL DEFAULT '0',
        `volumeUsd`               DOUBLE        NOT NULL DEFAULT '0',
        `tradingPairs`            INT(16)       NOT NULL DEFAULT '0',
        `socket`                  VARCHAR(20)   NOT NULL DEFAULT '' ,
        `exchangeUrl`             VARCHAR(200)  NOT NULL DEFAULT '' ,
        `updated`                 BIGINT(20)    NOT NULL DEFAULT '0'
        )
?>