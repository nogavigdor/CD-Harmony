<?php   
use DataAccess\DBConnector;

error_reporting(E_ALL);
ini_set('display_errors', 1);


//require(__DIR__ . '/db_constants.php');

// Create an instance of the DBConnector
$dbConnector = DBConnector::getInstance();
$db = $dbConnector->connectToDB();

// Check the connection
if (!$db) {
    die("Connection failed.");
}

// Load the JSON data with error checking
$jsonData = file_get_contents('./sql/artists_and_albums-29-10-23.json');

if ($jsonData === false) {
    die("Error reading JSON file.");
}

$data = json_decode($jsonData, true);
 
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error parsing JSON data: " . json_last_error_msg());
}

try {
    // Start a transaction
    $db->beginTransaction();
  
    // Iterate through the JSON data and insert records
    foreach ($data as $artistName => $albums) {
        // Insert the artist into the artists table
        $stmtArtist = $db->prepare("INSERT INTO artists (title) VALUES (:artistName)");
        $stmtArtist->bindParam(':artistName', $artistName, PDO::PARAM_STR);
        $stmtArtist->execute();

        $artistId = $db->lastInsertId();

        // Insert albums for the artist
        foreach ($albums as $album) {
            $albumName = $album['album_name'];
            $description = $album['description'];
            $releaseDate = date('Y-m-d', strtotime($album['release_date']));
            $imageName = pathinfo($album['image_url'], PATHINFO_BASENAME);
            $creationDate = date('Y-m-d H:i:s');

            // Insert album data into the products table
            $stmtProduct = $db->prepare("INSERT INTO products (title, product_description, creation_date) VALUES (:albumName, :albumDescription, :creationDate)");
            $stmtProduct->bindParam(':albumName', $albumName, PDO::PARAM_STR);
            $stmtProduct->bindParam(':albumDescription', $description, PDO::PARAM_STR);
            $stmtProduct->bindParam(':creationDate', $creationDate, PDO::PARAM_STR);
            $stmtProduct->execute();

            $productId = $db->lastInsertId();

            // Insert album images into the images_for_products table
            $stmtImages = $db->prepare("INSERT INTO images_for_products (image_name, main_image, product_id) VALUES (:imageName, 1, :productId)");
            $stmtImages->bindParam(':imageName', $imageName, PDO::PARAM_STR);
            $stmtImages->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmtImages->execute();

            // Insert tags into the Tags Table and Products_Tags Table
            if (is_array($album) && isset($album['tags']) && is_array($album['tags'])) {
                foreach ($album['tags'] as $tag) {
                    if (isset($tag['name'])) {
                        $tagName = $tag['name'];

                        // Insert or retrieve tag_id
                        $stmtTag = $db->prepare("INSERT INTO tags (title) VALUES (:tagName) ON DUPLICATE KEY UPDATE title = :tagName");
                        $stmtTag->bindParam(':tagName', $tagName, PDO::PARAM_STR);
                        $stmtTag->execute();

                        $stmtGetTagId = $db->prepare("SELECT tag_id FROM tags WHERE title = :tagName");
                        $stmtGetTagId->bindParam(':tagName', $tagName, PDO::PARAM_STR);
                        $stmtGetTagId->execute();

                        $tagId = $stmtGetTagId->fetchColumn();

                        // Insert entry into Products_Tags table
                        $stmtProductTag = $db->prepare("INSERT INTO products_tags (product_id, tag_id) VALUES (:productId, :tagId)");
                        $stmtProductTag->bindParam(':productId', $productId, PDO::PARAM_INT);
                        $stmtProductTag->bindParam(':tagId', $tagId, PDO::PARAM_INT);
                        $stmtProductTag->execute();
                    }

          
                }
            }//end of tags insertion

       
 
            
           // Insert album data into the cds table
           $stmtCds = $db->prepare("INSERT INTO cds (release_date, artist_id, product_id) VALUES (:releaseDate, :artistId, :productId)");
           $stmtCds->bindParam(':releaseDate', $releaseDate, PDO::PARAM_STR);
           $stmtCds->bindParam(':artistId', $artistId, PDO::PARAM_INT);
           $stmtCds->bindParam(':productId', $productId, PDO::PARAM_INT);
           $stmtCds->execute();

           // Insert album data into the product_variants table for "new" condition
           $priceNew = rand(1, 5) * 10 + 89.95;
           $quantityInStockNew = rand(0, 10);
           $isDeleted = 0;
           $conditionId = 1;
           $stmtConditionNew = $db->prepare("INSERT INTO product_variants (price, quantity_in_stock, product_id, condition_id, creation_date, is_deleted) VALUES (:priceNew, :quantityInStockNew, :productId, :conditionId, :creationDate, :isDeleted)");
           $stmtConditionNew->bindParam(':priceNew', $priceNew, PDO::PARAM_INT);
           $stmtConditionNew->bindParam(':quantityInStockNew', $quantityInStockNew, PDO::PARAM_INT);
           $stmtConditionNew->bindParam(':productId', $productId, PDO::PARAM_INT);
           $stmtConditionNew->bindParam(':conditionId', $conditionId, PDO::PARAM_INT);
           $stmtConditionNew->bindParam(':creationDate', $creationDate, PDO::PARAM_STR); 
           $stmtConditionNew->bindParam(':isDeleted', $isDeleted, PDO::PARAM_INT);   
           $stmtConditionNew->execute();

           // Insert album data into the product_variants table for "used" condition
           $priceUsed = $priceNew - 40;
           $quantityInStockUsed = rand(0, 10);
           $conditionId = 2;
           $isDeleted = 0;
           // Insert album data into the product_variants table for "new" condition
           $stmtConditionUsed = $db->prepare("INSERT INTO product_variants (price, quantity_in_stock, product_id, condition_id, creation_date, is_deleted) VALUES (:priceUsed, :quantityInStockUsed, :productId, :conditionId, :creationDate, :isDeleted)");
           $stmtConditionUsed->bindParam(':priceUsed', $priceUsed, PDO::PARAM_INT);
           $stmtConditionUsed->bindParam(':quantityInStockUsed', $quantityInStockUsed, PDO::PARAM_INT);
           $stmtConditionUsed->bindParam(':productId', $productId, PDO::PARAM_INT);
           $stmtConditionUsed->bindParam(':conditionId', $conditionId, PDO::PARAM_INT);
           $stmtConditionUsed->bindParam(':creationDate', $creationDate, PDO::PARAM_STR);
           $stmtConditionUsed->bindParam(':isDeleted', $isDeleted, PDO::PARAM_INT);
           $stmtConditionUsed->execute();
       
        }

    }
//last insterted product variant to use later on for insertion of speciall offer. s
$lastInsertedProductVariant =  $db->lastInsertId();

//insertion of Zipcode table

$data = [

    ['0783', 'Facility'],
    ['0784', 'H&M'],
    ['0785', 'Bestseller '],
    ['0800', 'Høje Taastrup'],
    ['0900', 'København C'],
    ['0913', 'Københavns Pakkecent'],
    ['0914', 'Københavns Pakkecent'],
    ['0917', 'Københavns Pakkecent'],
    ['0960', 'Udland'],
    ['0999', 'København C'],
    ['1000', 'København K'],
    ['1050', 'København K'],
    ['1051', 'København K'],
    ['1052', 'København K'],
    ['1053', 'København K'],
    ['1054', 'København K'],
    ['1055', 'København K'],
    ['1056', 'København K'],
    ['1057', 'København K'],
    ['1058', 'København K'],
    ['1059', 'København K'],
    ['1060', 'København K'],
    ['1061', 'København K'],
    ['1062', 'København K'],
    ['1063', 'København K'],
    ['1064', 'København K'],
    ['1065', 'København K'],
    ['1066', 'København K'],
    ['1067', 'København K'],
    ['1068', 'København K'],
    ['1069', 'København K'],
    ['1070', 'København K'],
    ['1071', 'København K'],
    ['1072', 'København K'],
    ['1073', 'København K'],
    ['1074', 'København K'],
    ['1092', 'København K'],
    ['1093', 'København K'],
    ['1095', 'København K'],
    ['1098', 'København K'],
    ['1100', 'København K'],
    ['1101', 'København K'],
    ['1102', 'København K'],
    ['1103', 'København K'],
    ['1104', 'København K'],
    ['1105', 'København K'],
    ['1106', 'København K'],
    ['1107', 'København K'],
    ['1110', 'København K'],
    ['1111', 'København K'],
    ['1112', 'København K'],
    ['1113', 'København K'],
    ['1114', 'København K'],
    ['1115', 'København K'],
    ['1116', 'København K'],
    ['1117', 'København K'],
    ['1118', 'København K'],
    ['1119', 'København K'],
    ['1120', 'København K'],
    ['1121', 'København K'],
    ['1122', 'København K'],
    ['1123', 'København K'],
    ['1124', 'København K'],
    ['1125', 'København K'],
    ['1126', 'København K'],
    ['1127', 'København K'],
    ['1128', 'København K'],
    ['1129', 'København K'],
    ['1130', 'København K'],
    ['1131', 'København K'],
    ['1140', 'København K'],
    ['1147', 'København K'],
    ['1148', 'København K'],
    ['1150', 'København K'],
    ['1151', 'København K'],
    ['1152', 'København K'],
    ['1153', 'København K'],
    ['1154', 'København K'],
    ['1155', 'København K'],
    ['1156', 'København K'],
    ['1157', 'København K'],
    ['1158', 'København K'],
    ['1159', 'København K'],
    ['1160', 'København K'],
    ['1161', 'København K'],
    ['1162', 'København K'],
    ['1164', 'København K'],
    ['1165', 'København K'],
    ['1166', 'København K'],
    ['1167', 'København K'],
    ['1168', 'København K'],
    ['1169', 'København K'],
    ['1170', 'København K'],
    ['1171', 'København K'],
    ['1172', 'København K'],
    ['1173', 'København K'],
    ['1174', 'København K'],
    ['1175', 'København K'],
    ['1200', 'København K'],
    ['1201', 'København K'],
    ['1202', 'København K'],
    ['1203', 'København K'],
    ['1204', 'København K'],
    ['1205', 'København K'],
    ['1206', 'København K'],
    ['1207', 'København K'],
    ['1208', 'København K'],
    ['1209', 'København K'],
    ['1210', 'København K'],
    ['1211', 'København K'],
    ['1213', 'København K'],
    ['1214', 'København K'],
    ['1215', 'København K'],
    ['1216', 'København K'],
    ['1217', 'København K'],
    ['1218', 'København K'],
    ['1219', 'København K'],
    ['1220', 'København K'],
    ['1221', 'København K'],
    ['1240', 'København K'],
    ['1250', 'København K'],
    ['1251', 'København K'],
    ['1252', 'København K'],
    ['1253', 'København K'],
    ['1254', 'København K'],
    ['1255', 'København K'],
    ['1256', 'København K'],
    ['1257', 'København K'],
    ['1259', 'København K'],
    ['1260', 'København K'],
    ['1261', 'København K'],
    ['1263', 'København K'],
    ['1264', 'København K'],
    ['1265', 'København K'],
    ['1266', 'København K'],
    ['1267', 'København K'],
    ['1268', 'København K'],
    ['1270', 'København K'],
    ['1271', 'København K'],
    ['1300', 'København K'],
    ['1301', 'København K'],
    ['1302', 'København K'],
    ['1303', 'København K'],
    ['1304', 'København K'],
    ['1306', 'København K'],
    ['1307', 'København K'],
    ['1308', 'København K'],
    ['1309', 'København K'],
    ['1310', 'København K'],
    ['1311', 'København K'],
    ['1312', 'København K'],
    ['1313', 'København K'],
    ['1314', 'København K'],
    ['1315', 'København K'],
    ['1316', 'København K'],
    ['1317', 'København K'],
    ['1318', 'København K'],
    ['1319', 'København K'],
    ['1320', 'København K'],
    ['1321', 'København K'],
    ['1322', 'København K'],
    ['1323', 'København K'],
    ['1324', 'København K'],
    ['1325', 'København K'],
    ['1326', 'København K'],
    ['1327', 'København K'],
    ['1328', 'København K'],
    ['1329', 'København K'],
    ['1350', 'København K'],
    ['1352', 'København K'],
    ['1353', 'København K'],
    ['1354', 'København K'],
    ['1355', 'København K'],
    ['1356', 'København K'],
    ['1357', 'København K'],
    ['1358', 'København K'],
    ['1359', 'København K'],
    ['1360', 'København K'],
    ['1361', 'København K'],
    ['1362', 'København K'],
    ['1363', 'København K'],
    ['1364', 'København K'],
    ['1365', 'København K'],
    ['1366', 'København K'],
    ['1367', 'København K'],
    ['1368', 'København K'],
    ['1369', 'København K'],
    ['1370', 'København K'],
    ['1371', 'København K'],
    ['1400', 'København K'],
    ['1401', 'København K'],
    ['1402', 'København K'],
    ['1403', 'København K'],
    ['1406', 'København K'],
    ['1407', 'København K'],
    ['1408', 'København K'],
    ['1409', 'København K'],
    ['1410', 'København K'],
    ['1411', 'København K'],
    ['1412', 'København K'],
    ['1413', 'København K'],
    ['1414', 'København K'],
    ['1415', 'København K'],
    ['1416', 'København K'],
    ['1417', 'København K'],
    ['1418', 'København K'],
    ['1419', 'København K'],
    ['1420', 'København K'],
    ['1421', 'København K'],
    ['1422', 'København K'],
    ['1423', 'København K'],
    ['1424', 'København K'],
    ['1425', 'København K'],
    ['1426', 'København K'],
    ['1427', 'København K'],
    ['1428', 'København K'],
    ['1429', 'København K'],
    ['1430', 'København K'],
    ['1432', 'København K'],
    ['1433', 'København K'],
    ['1434', 'København K'],
    ['1435', 'København K'],
    ['1436', 'København K'],
    ['1437', 'København K'],
    ['1438', 'København K'],
    ['1439', 'København K'],
    ['1440', 'København K'],
    ['1441', 'København K'],
    ['1448', 'København K'],
    ['1450', 'København K'],
    ['1451', 'København K'],
    ['1452', 'København K'],
    ['1453', 'København K'],
    ['1454', 'København K'],
    ['1455', 'København K'],
    ['1456', 'København K'],
    ['1457', 'København K'],
    ['1458', 'København K'],
    ['1459', 'København K'],
    ['1460', 'København K'],
    ['1462', 'København K'],
    ['1463', 'København K'],
    ['1464', 'København K'],
    ['1466', 'København K'],
    ['1467', 'København K'],
    ['1468', 'København K'],
    ['1470', 'København K'],
    ['1471', 'København K'],
    ['1472', 'København K'],
    ['1473', 'København K'],
    ['1500', 'København V'],
    ['1532', 'København V'],
    ['1533', 'København V'],
    ['1550', 'København V'],
    ['1551', 'København V'],
    ['1552', 'København V'],
    ['1553', 'København V'],
    ['1554', 'København V'],
    ['1555', 'København V'],
    ['1556', 'København V'],
    ['1557', 'København V'],
    ['1558', 'København V'],
    ['1559', 'København V'],
    ['1560', 'København V'],
    ['1561', 'København V'],
    ['1562', 'København V'],
    ['1563', 'København V'],
    ['1564', 'København V'],
    ['1566', 'København V'],
    ['1567', 'København V'],
    ['1568', 'København V'],
    ['1569', 'København V'],
    ['1570', 'København V'],
    ['1571', 'København V'],
    ['1572', 'København V'],
    ['1573', 'København V'],
    ['1574', 'København V'],
    ['1575', 'København V'],
    ['1576', 'København V'],
    ['1577', 'København V'],
    ['1592', 'København V'],
    ['1599', 'København V'],
    ['1600', 'København V'],
    ['1601', 'København V'],
    ['1602', 'København V'],
    ['1603', 'København V'],
    ['1604', 'København V'],
    ['1605', 'København V'],
    ['1606', 'København V'],
    ['1607', 'København V'],
    ['1608', 'København V'],
    ['1609', 'København V'],
    ['1610', 'København V'],
    ['1611', 'København V'],
    ['1612', 'København V'],
    ['1613', 'København V'],
    ['1614', 'København V'],
    ['1615', 'København V'],
    ['1616', 'København V'],
    ['1617', 'København V'],
    ['1618', 'København V'],
    ['1619', 'København V'],
    ['1620', 'København V'],
    ['1621', 'København V'],
    ['1622', 'København V'],
    ['1623', 'København V'],
    ['1624', 'København V'],
    ['1630', 'København V'],
    ['1631', 'København V'],
    ['1632', 'København V'],
    ['1633', 'København V'],
    ['1634', 'København V'],
    ['1635', 'København V'],
    ['1650', 'København V'],
    ['1651', 'København V'],
    ['1652', 'København V'],
    ['1653', 'København V'],
    ['1654', 'København V'],
    ['1655', 'København V'],
    ['1656', 'København V'],
    ['1657', 'København V'],
    ['1658', 'København V'],
    ['1659', 'København V'],
    ['1660', 'København V'],
    ['1661', 'København V'],
    ['1662', 'København V'],
    ['1663', 'København V'],
    ['1664', 'København V'],
    ['1665', 'København V'],
    ['1666', 'København V'],
    ['1667', 'København V'],
    ['1668', 'København V'],
    ['1669', 'København V'],
    ['1670', 'København V'],
    ['1671', 'København V'],
    ['1672', 'København V'],
    ['1673', 'København V'],
    ['1674', 'København V'],
    ['1675', 'København V'],
    ['1676', 'København V'],
    ['1677', 'København V'],
    ['1699', 'København V'],
    ['1700', 'København V'],
    ['1701', 'København V'],
    ['1702', 'København V'],
    ['1703', 'København V'],
    ['1704', 'København V'],
    ['1705', 'København V'],
    ['1706', 'København V'],
    ['1707', 'København V'],
    ['1708', 'København V'],
    ['1709', 'København V'],
    ['1710', 'København V'],
    ['1711', 'København V'],
    ['1712', 'København V'],
    ['1714', 'København V'],
    ['1715', 'København V'],
    ['1716', 'København V'],
    ['1717', 'København V'],
    ['1718', 'København V'],
    ['1719', 'København V'],
    ['1720', 'København V'],
    ['1721', 'København V'],
    ['1722', 'København V'],
    ['1723', 'København V'],
    ['1724', 'København V'],
    ['1725', 'København V'],
    ['1726', 'København V'],
    ['1727', 'København V'],
    ['1728', 'København V'],
    ['1729', 'København V'],
    ['1730', 'København V'],
    ['1731', 'København V'],
    ['1732', 'København V'],
    ['1733', 'København V'],
    ['1734', 'København V'],
    ['1735', 'København V'],
    ['1736', 'København V'],
    ['1737', 'København V'],
    ['1738', 'København V'],
    ['1739', 'København V'],
    ['1749', 'København V'],
    ['1750', 'København V'],
    ['1751', 'København V'],
    ['1752', 'København V'],
    ['1753', 'København V'],
    ['1754', 'København V'],
    ['1755', 'København V'],
    ['1756', 'København V'],
    ['1757', 'København V'],
    ['1758', 'København V'],
    ['1759', 'København V'],
    ['1760', 'København V'],
    ['1761', 'København V'],
    ['1762', 'København V'],
    ['1763', 'København V'],
    ['1764', 'København V'],
    ['1765', 'København V'],
    ['1766', 'København V'],
    ['1770', 'København V'],
    ['1771', 'København V'],
    ['1772', 'København V'],
    ['1773', 'København V'],
    ['1774', 'København V'],
    ['1775', 'København V'],
    ['1777', 'København V'],
    ['1780', 'København V'],
    ['1785', 'København V'],
    ['1786', 'København V'],
    ['1787', 'København V'],
    ['1790', 'København V'],
    ['1799', 'København V'],
    ['1800', 'Frederiksberg C'],
    ['1801', 'Frederiksberg C'],
    ['1802', 'Frederiksberg C'],
    ['1803', 'Frederiksberg C'],
    ['1804', 'Frederiksberg C'],
    ['1805', 'Frederiksberg C'],
    ['1806', 'Frederiksberg C'],
    ['1807', 'Frederiksberg C'],
    ['1808', 'Frederiksberg C'],
    ['1809', 'Frederiksberg C'],
    ['1810', 'Frederiksberg C'],
    ['1811', 'Frederiksberg C'],
    ['1812', 'Frederiksberg C'],
    ['1813', 'Frederiksberg C'],
    ['1814', 'Frederiksberg C'],
    ['1815', 'Frederiksberg C'],
    ['1816', 'Frederiksberg C'],
    ['1817', 'Frederiksberg C'],
    ['1818', 'Frederiksberg C'],
    ['1819', 'Frederiksberg C'],
    ['1820', 'Frederiksberg C'],
    ['1822', 'Frederiksberg C'],
    ['1823', 'Frederiksberg C'],
    ['1824', 'Frederiksberg C'],
    ['1825', 'Frederiksberg C'],
    ['1826', 'Frederiksberg C'],
    ['1827', 'Frederiksberg C'],
    ['1828', 'Frederiksberg C'],
    ['1829', 'Frederiksberg C'],
    ['1850', 'Frederiksberg C'],
    ['1851', 'Frederiksberg C'],
    ['1852', 'Frederiksberg C'],
    ['1853', 'Frederiksberg C'],
    ['1854', 'Frederiksberg C'],
    ['1855', 'Frederiksberg C'],
    ['1856', 'Frederiksberg C'],
    ['1857', 'Frederiksberg C'],
    ['1860', 'Frederiksberg C'],
    ['1861', 'Frederiksberg C'],
    ['1862', 'Frederiksberg C'],
    ['1863', 'Frederiksberg C'],
    ['1864', 'Frederiksberg C'],
    ['1865', 'Frederiksberg C'],
    ['1866', 'Frederiksberg C'],
    ['1867', 'Frederiksberg C'],
    ['1868', 'Frederiksberg C'],
    ['1870', 'Frederiksberg C'],
    ['1871', 'Frederiksberg C'],
    ['1872', 'Frederiksberg C'],
    ['1873', 'Frederiksberg C'],
    ['1874', 'Frederiksberg C'],
    ['1875', 'Frederiksberg C'],
    ['1876', 'Frederiksberg C'],
    ['1877', 'Frederiksberg C'],
    ['1878', 'Frederiksberg C'],
    ['1879', 'Frederiksberg C'],
    ['1900', 'Frederiksberg C'],
    ['1901', 'Frederiksberg C'],
    ['1902', 'Frederiksberg C'],
    ['1903', 'Frederiksberg C'],
    ['1904', 'Frederiksberg C'],
    ['1905', 'Frederiksberg C'],
    ['1906', 'Frederiksberg C'],
    ['1908', 'Frederiksberg C'],
    ['1909', 'Frederiksberg C'],
    ['1910', 'Frederiksberg C'],
    ['1911', 'Frederiksberg C'],
    ['1912', 'Frederiksberg C'],
    ['1913', 'Frederiksberg C'],
    ['1914', 'Frederiksberg C'],
    ['1915', 'Frederiksberg C'],
    ['1916', 'Frederiksberg C'],
    ['1917', 'Frederiksberg C'],
    ['1920', 'Frederiksberg C'],
    ['1921', 'Frederiksberg C'],
    ['1922', 'Frederiksberg C'],
    ['1923', 'Frederiksberg C'],
    ['1924', 'Frederiksberg C'],
    ['1925', 'Frederiksberg C'],
    ['1926', 'Frederiksberg C'],
    ['1927', 'Frederiksberg C'],
    ['1928', 'Frederiksberg C'],
    ['1950', 'Frederiksberg C'],
    ['1951', 'Frederiksberg C'],
    ['1952', 'Frederiksberg C'],
    ['1953', 'Frederiksberg C'],
    ['1954', 'Frederiksberg C'],
    ['1955', 'Frederiksberg C'],
    ['1956', 'Frederiksberg C'],
    ['1957', 'Frederiksberg C'],
    ['1958', 'Frederiksberg C'],
    ['1959', 'Frederiksberg C'],
    ['1960', 'Frederiksberg C'],
    ['1961', 'Frederiksberg C'],
    ['1962', 'Frederiksberg C'],
    ['1963', 'Frederiksberg C'],
    ['1964', 'Frederiksberg C'],
    ['1965', 'Frederiksberg C'],
    ['1966', 'Frederiksberg C'],
    ['1967', 'Frederiksberg C'],
    ['1970', 'Frederiksberg C'],
    ['1971', 'Frederiksberg C'],
    ['1972', 'Frederiksberg C'],
    ['1973', 'Frederiksberg C'],
    ['1974', 'Frederiksberg C'],
    ['2000', 'Frederiksberg'],
    ['2100', 'København Ø'],
    ['2150', 'Nordhavn'],
    ['2200', 'København N'],
    ['2300', 'København S'],
    ['2400', 'København NV'],
    ['2450', 'København SV'],
    ['2500', 'Valby'],
    ['2600', 'Glostrup'],
    ['2605', 'Brøndby'],
    ['2610', 'Rødovre'],
    ['2620', 'Albertslund'],
    ['2625', 'Vallensbæk'],
    ['2630', 'Taastrup'],
    ['2635', 'Ishøj'],
    ['2640', 'Hedehusene'],
    ['2650', 'Hvidovre'],
    ['2660', 'Brøndby Strand'],
    ['2665', 'Vallensbæk Strand'],
    ['2670', 'Greve'],
    ['2680', 'Solrød Strand'],
    ['2690', 'Karlslunde'],
    ['2700', 'Brønshøj'],
    ['2720', 'Vanløse'],
    ['2730', 'Herlev'],
    ['2740', 'Skovlunde'],
    ['2750', 'Ballerup'],
    ['2760', 'Måløv'],
    ['2765', 'Smørum'],
    ['2770', 'Kastrup'],
    ['2791', 'Dragør'],
    ['2800', 'Kongens Lyngby'],
    ['2820', 'Gentofte'],
    ['2830', 'Virum'],
    ['2840', 'Holte'],
    ['2850', 'Nærum'],
    ['2860', 'Søborg'],
    ['2870', 'Dyssegård'],
    ['2880', 'Bagsværd'],
    ['2900', 'Hellerup'],
    ['2920', 'Charlottenlund'],
    ['2930', 'Klampenborg'],
    ['2942', 'Skodsborg'],
    ['2950', 'Vedbæk'],
    ['2960', 'Rungsted Kyst'],
    ['2970', 'Hørsholm'],
    ['2980', 'Kokkedal'],
    ['2990', 'Nivå'],
    ['3000', 'Helsingør'],
    ['3050', 'Humlebæk'],
    ['3060', 'Espergærde'],
    ['3070', 'Snekkersten'],
    ['3080', 'Tikøb'],
    ['3100', 'Hornbæk'],
    ['3120', 'Dronningmølle'],
    ['3140', 'Ålsgårde'],
    ['3150', 'Hellebæk'],
    ['3210', 'Vejby'],
    ['3220', 'Tisvildeleje'],
    ['3230', 'Græsted'],
    ['3250', 'Gilleleje'],
    ['3300', 'Frederiksværk'],
    ['3310', 'Ølsted'],
    ['3320', 'Skævinge'],
    ['3330', 'Gørløse'],
    ['3360', 'Liseleje'],
    ['3370', 'Melby'],
    ['3390', 'Hundested'],
    ['3400', 'Hillerød'],
    ['3450', 'Allerød'],
    ['3460', 'Birkerød'],
    ['3480', 'Fredensborg'],
    ['3490', 'Kvistgård'],
    ['3500', 'Værløse'],
    ['3520', 'Farum'],
    ['3540', 'Lynge'],
    ['3550', 'Slangerup'],
    ['3600', 'Frederikssund'],
    ['3630', 'Jægerspris'],
    ['3650', 'Ølstykke'],
    ['3660', 'Stenløse'],
    ['3670', 'Veksø Sjælland'],
    ['3700', 'Rønne'],
    ['3720', 'Aakirkeby'],
    ['3730', 'Nexø'],
    ['3740', 'Svaneke'],
    ['3751', 'Østermarie'],
    ['3760', 'Gudhjem'],
    ['3770', 'Allinge'],
    ['3782', 'Klemensker'],
    ['3790', 'Hasle'],
    ['4000', 'Roskilde'],
    ['4030', 'Tune'],
    ['4040', 'Jyllinge'],
    ['4050', 'Skibby'],
    ['4060', 'Kirke Såby'],
    ['4070', 'Kirke Hyllinge'],
    ['4100', 'Ringsted'],
    ['4130', 'Viby Sjælland'],
    ['4140', 'Borup'],
    ['4160', 'Herlufmagle'],
    ['4171', 'Glumsø'],
    ['4173', 'Fjenneslev'],
    ['4174', 'Jystrup Midtsj'],
    ['4180', 'Sorø'],
    ['4190', 'Munke Bjergby'],
    ['4200', 'Slagelse'],
    ['4220', 'Korsør'],
    ['4230', 'Skælskør'],
    ['4241', 'Vemmelev'],
    ['4242', 'Boeslunde'],
    ['4243', 'Rude'],
    ['4244', 'Agersø'],
    ['4245', 'Omø'],
    ['4250', 'Fuglebjerg'],
    ['4261', 'Dalmose'],
    ['4262', 'Sandved'],
    ['4270', 'Høng'],
    ['4281', 'Gørlev'],
    ['4291', 'Ruds Vedby'],
    ['4293', 'Dianalund'],
    ['4295', 'Stenlille'],
    ['4296', 'Nyrup'],
    ['4300', 'Holbæk'],
    ['4305', 'Orø'],
    ['4320', 'Lejre'],
    ['4330', 'Hvalsø'],
    ['4340', 'Tølløse'],
    ['4350', 'Ugerløse'],
    ['4360', 'Kirke Eskilstrup'],
    ['4370', 'Store Merløse'],
    ['4390', 'Vipperød'],
    ['4400', 'Kalundborg'],
    ['4420', 'Regstrup'],
    ['4440', 'Mørkøv'],
    ['4450', 'Jyderup'],
    ['4460', 'Snertinge'],
    ['4470', 'Svebølle'],
    ['4480', 'Store Fuglede'],
    ['4490', 'Jerslev Sjælland'],
    ['4500', 'Nykøbing Sj'],
    ['4520', 'Svinninge'],
    ['4532', 'Gislinge'],
    ['4534', 'Hørve'],
    ['4540', 'Fårevejle'],
    ['4550', 'Asnæs'],
    ['4560', 'Vig'],
    ['4571', 'Grevinge'],
    ['4572', 'Nørre Asmindrup'],
    ['4573', 'Højby'],
    ['4581', 'Rørvig'],
    ['4583', 'Sjællands Odde'],
    ['4591', 'Føllenslev'],
    ['4592', 'Sejerø'],
    ['4593', 'Eskebjerg'],
    ['4600', 'Køge'],
    ['4621', 'Gadstrup'],
    ['4622', 'Havdrup'],
    ['4623', 'Lille Skensved'],
    ['4632', 'Bjæverskov'],
    ['4640', 'Faxe'],
    ['4652', 'Hårlev'],
    ['4653', 'Karise'],
    ['4654', 'Faxe Ladeplads'],
    ['4660', 'Store Heddinge'],
    ['4671', 'Strøby'],
    ['4672', 'Klippinge'],
    ['4673', 'Rødvig Stevns'],
    ['4681', 'Herfølge'],
    ['4682', 'Tureby'],
    ['4683', 'Rønnede'],
    ['4684', 'Holmegaard'],
    ['4690', 'Haslev'],
    ['4700', 'Næstved'],
    ['4720', 'Præstø'],
    ['4733', 'Tappernøje'],
    ['4735', 'Mern'],
    ['4736', 'Karrebæksminde'],
    ['4750', 'Lundby'],
    ['4760', 'Vordingborg'],
    ['4771', 'Kalvehave'],
    ['4772', 'Langebæk'],
    ['4773', 'Stensved'],
    ['4780', 'Stege'],
    ['4791', 'Borre'],
    ['4792', 'Askeby'],
    ['4793', 'Bogø By'],
    ['4800', 'Nykøbing F'],
    ['4840', 'Nørre Alslev'],
    ['4850', 'Stubbekøbing'],
    ['4862', 'Guldborg'],
    ['4863', 'Eskilstrup'],
    ['4871', 'Horbelev'],
    ['4872', 'Idestrup'],
    ['4873', 'Væggerløse'],
    ['4874', 'Gedser'],
    ['4880', 'Nysted'],
    ['4891', 'Toreby L'],
    ['4892', 'Kettinge'],
    ['4894', 'Øster Ulslev'],
    ['4895', 'Errindlev'],
    ['4900', 'Nakskov'],
    ['4912', 'Harpelunde'],
    ['4913', 'Horslunde'],
    ['4920', 'Søllested'],
    ['4930', 'Maribo'],
    ['4941', 'Bandholm'],
    ['4942', 'Askø'],
    ['4943', 'Torrig L'],
    ['4944', 'Fejø'],
    ['4945', 'Femø'],
    ['4951', 'Nørreballe'],
    ['4952', 'Stokkemarke'],
    ['4953', 'Vesterborg'],
    ['4960', 'Holeby'],
    ['4970', 'Rødby'],
    ['4983', 'Dannemare'],
    ['4990', 'Sakskøbing'],
    ['5000', 'Odense C'],
    ['5200', 'Odense V'],
    ['5210', 'Odense NV'],
    ['5220', 'Odense SØ'],
    ['5230', 'Odense M'],
    ['5240', 'Odense NØ'],
    ['5250', 'Odense SV'],
    ['5260', 'Odense S'],
    ['5270', 'Odense N'],
    ['5290', 'Marslev'],
    ['5300', 'Kerteminde'],
    ['5320', 'Agedrup'],
    ['5330', 'Munkebo'],
    ['5350', 'Rynkeby'],
    ['5370', 'Mesinge'],
    ['5380', 'Dalby'],
    ['5390', 'Martofte'],
    ['5400', 'Bogense'],
    ['5450', 'Otterup'],
    ['5462', 'Morud'],
    ['5463', 'Harndrup'],
    ['5464', 'Brenderup Fyn'],
    ['5466', 'Asperup'],
    ['5471', 'Søndersø'],
    ['5474', 'Veflinge'],
    ['5485', 'Skamby'],
    ['5491', 'Blommenslyst'],
    ['5492', 'Vissenbjerg'],
    ['5500', 'Middelfart'],
    ['5540', 'Ullerslev'],
    ['5550', 'Langeskov'],
    ['5560', 'Aarup'],
    ['5580', 'Nørre Aaby'],
    ['5591', 'Gelsted'],
    ['5592', 'Ejby'],
    ['5600', 'Faaborg'],
    ['5601', 'Lyø'],
    ['5602', 'Avernakø'],
    ['5603', 'Bjørnø'],
    ['5610', 'Assens'],
    ['5620', 'Glamsbjerg'],
    ['5631', 'Ebberup'],
    ['5642', 'Millinge'],
    ['5672', 'Broby'],
    ['5683', 'Haarby'],
    ['5690', 'Tommerup'],
    ['5700', 'Svendborg'],
    ['5750', 'Ringe'],
    ['5762', 'Vester Skerninge'],
    ['5771', 'Stenstrup'],
    ['5772', 'Kværndrup'],
    ['5792', 'Årslev'],
    ['5800', 'Nyborg'],
    ['5853', 'Ørbæk'],
    ['5854', 'Gislev'],
    ['5856', 'Ryslinge'],
    ['5863', 'Ferritslev Fyn'],
    ['5871', 'Frørup'],
    ['5874', 'Hesselager'],
    ['5881', 'Skårup Fyn'],
    ['5882', 'Vejstrup'],
    ['5883', 'Oure'],
    ['5884', 'Gudme'],
    ['5892', 'Gudbjerg Sydfyn'],
    ['5900', 'Rudkøbing'],
    ['5932', 'Humble'],
    ['5935', 'Bagenkop'],
    ['5943', 'Strynø'],
    ['5953', 'Tranekær'],
    ['5960', 'Marstal'],
    ['5965', 'Birkholm'],
    ['5970', 'Ærøskøbing'],
    ['5985', 'Søby Ærø'],
    ['6000', 'Kolding'],
    ['6040', 'Egtved'],
    ['6051', 'Almind'],
    ['6052', 'Viuf'],
    ['6064', 'Jordrup'],
    ['6070', 'Christiansfeld'],
    ['6091', 'Bjert'],
    ['6092', 'Sønder Stenderup'],
    ['6093', 'Sjølund'],
    ['6094', 'Hejls'],
    ['6100', 'Haderslev'],
    ['6200', 'Aabenraa'],
    ['6210', 'Barsø'],
    ['6230', 'Rødekro'],
    ['6240', 'Løgumkloster'],
    ['6261', 'Bredebro'],
    ['6270', 'Tønder'],
    ['6280', 'Højer'],
    ['6300', 'Gråsten'],
    ['6310', 'Broager'],
    ['6320', 'Egernsund'],
    ['6330', 'Padborg'],
    ['6340', 'Kruså'],
    ['6360', 'Tinglev'],
    ['6372', 'Bylderup-Bov'],
    ['6392', 'Bolderslev'],
    ['6400', 'Sønderborg'],
    ['6430', 'Nordborg'],
    ['6440', 'Augustenborg'],
    ['6470', 'Sydals'],
    ['6500', 'Vojens'],
    ['6510', 'Gram'],
    ['6520', 'Toftlund'],
    ['6534', 'Agerskov'],
    ['6535', 'Branderup J'],
    ['6541', 'Bevtoft'],
    ['6560', 'Sommersted'],
    ['6580', 'Vamdrup'],
    ['6600', 'Vejen'],
    ['6621', 'Gesten'],
    ['6622', 'Bække'],
    ['6623', 'Vorbasse'],
    ['6630', 'Rødding'],
    ['6640', 'Lunderskov'],
    ['6650', 'Brørup'],
    ['6660', 'Lintrup'],
    ['6670', 'Holsted'],
    ['6682', 'Hovborg'],
    ['6683', 'Føvling'],
    ['6690', 'Gørding'],
    ['6700', 'Esbjerg'],
    ['6705', 'Esbjerg Ø'],
    ['6710', 'Esbjerg V'],
    ['6715', 'Esbjerg N'],
    ['6720', 'Fanø'],
    ['6731', 'Tjæreborg'],
    ['6740', 'Bramming'],
    ['6752', 'Glejbjerg'],
    ['6753', 'Agerbæk'],
    ['6760', 'Ribe'],
    ['6771', 'Gredstedbro'],
    ['6780', 'Skærbæk'],
    ['6792', 'Rømø'],
    ['6800', 'Varde'],
    ['6818', 'Årre'],
    ['6823', 'Ansager'],
    ['6830', 'Nørre Nebel'],
    ['6840', 'Oksbøl'],
    ['6851', 'Janderup Vestj'],
    ['6852', 'Billum'],
    ['6853', 'Vejers Strand'],
    ['6854', 'Henne'],
    ['6855', 'Outrup'],
    ['6857', 'Blåvand'],
    ['6862', 'Tistrup'],
    ['6870', 'Ølgod'],
    ['6880', 'Tarm'],
    ['6893', 'Hemmet'],
    ['6900', 'Skjern'],
    ['6920', 'Videbæk'],
    ['6933', 'Kibæk'],
    ['6940', 'Lem St'],
    ['6950', 'Ringkøbing'],
    ['6960', 'Hvide Sande'],
    ['6971', 'Spjald'],
    ['6973', 'Ørnhøj'],
    ['6980', 'Tim'],
    ['6990', 'Ulfborg'],
    ['7007', 'Fredericia'],
    ['7080', 'Børkop'],
    ['7100', 'Vejle'],
    ['7120', 'Vejle Øst'],
    ['7130', 'Juelsminde'],
    ['7140', 'Stouby'],
    ['7150', 'Barrit'],
    ['7160', 'Tørring'],
    ['7171', 'Uldum'],
    ['7173', 'Vonge'],
    ['7182', 'Bredsten'],
    ['7183', 'Randbøl'],
    ['7184', 'Vandel'],
    ['7190', 'Billund'],
    ['7200', 'Grindsted'],
    ['7250', 'Hejnsvig'],
    ['7260', 'Sønder Omme'],
    ['7270', 'Stakroge'],
    ['7280', 'Sønder Felding'],
    ['7300', 'Jelling'],
    ['7321', 'Gadbjerg'],
    ['7323', 'Give'],
    ['7330', 'Brande'],
    ['7361', 'Ejstrupholm'],
    ['7362', 'Hampen'],
    ['7400', 'Herning'],
    ['7430', 'Ikast'],
    ['7441', 'Bording'],
    ['7442', 'Engesvang'],
    ['7451', 'Sunds'],
    ['7470', 'Karup J'],
    ['7480', 'Vildbjerg'],
    ['7490', 'Aulum'],
    ['7500', 'Holstebro'],
    ['7540', 'Haderup'],
    ['7550', 'Sørvad'],
    ['7560', 'Hjerm'],
    ['7570', 'Vemb'],
    ['7600', 'Struer'],
    ['7620', 'Lemvig'],
    ['7650', 'Bøvlingbjerg'],
    ['7660', 'Bækmarksbro'],
    ['7673', 'Harboøre'],
    ['7680', 'Thyborøn'],
    ['7700', 'Thisted'],
    ['7730', 'Hanstholm'],
    ['7741', 'Frøstrup'],
    ['7742', 'Vesløs'],
    ['7752', 'Snedsted'],
    ['7755', 'Bedsted Thy'],
    ['7760', 'Hurup Thy'],
    ['7770', 'Vestervig'],
    ['7790', 'Thyholm'],
    ['7800', 'Skive'],
    ['7830', 'Vinderup'],
    ['7840', 'Højslev'],
    ['7850', 'Stoholm Jyll'],
    ['7860', 'Spøttrup'],
    ['7870', 'Roslev'],
    ['7884', 'Fur'],
    ['7900', 'Nykøbing M'],
    ['7950', 'Erslev'],
    ['7960', 'Karby'],
    ['7970', 'Redsted M'],
    ['7980', 'Vils'],
    ['7990', 'Øster Assels'],
    ['8000', 'Aarhus C'],
    ['8200', 'Aarhus N'],
    ['8210', 'Aarhus V'],
    ['8220', 'Brabrand'],
    ['8230', 'Åbyhøj'],
    ['8240', 'Risskov'],
    ['8245', 'Risskov Ø'],
    ['8250', 'Egå'],
    ['8260', 'Viby J'],
    ['8270', 'Højbjerg'],
    ['8300', 'Odder'],
    ['8305', 'Samsø'],
    ['8310', 'Tranbjerg J'],
    ['8320', 'Mårslet'],
    ['8330', 'Beder'],
    ['8340', 'Malling'],
    ['8350', 'Hundslund'],
    ['8355', 'Solbjerg'],
    ['8361', 'Hasselager'],
    ['8362', 'Hørning'],
    ['8370', 'Hadsten'],
    ['8380', 'Trige'],
    ['8381', 'Tilst'],
    ['8382', 'Hinnerup'],
    ['8400', 'Ebeltoft'],
    ['8410', 'Rønde'],
    ['8420', 'Knebel'],
    ['8444', 'Balle'],
    ['8450', 'Hammel'],
    ['8462', 'Harlev J'],
    ['8464', 'Galten'],
    ['8471', 'Sabro'],
    ['8472', 'Sporup'],
    ['8500', 'Grenaa'],
    ['8520', 'Lystrup'],
    ['8530', 'Hjortshøj'],
    ['8541', 'Skødstrup'],
    ['8543', 'Hornslet'],
    ['8544', 'Mørke'],
    ['8550', 'Ryomgård'],
    ['8560', 'Kolind'],
    ['8570', 'Trustrup'],
    ['8581', 'Nimtofte'],
    ['8585', 'Glesborg'],
    ['8586', 'Ørum Djurs'],
    ['8592', 'Anholt'],
    ['8600', 'Silkeborg'],
    ['8620', 'Kjellerup'],
    ['8632', 'Lemming'],
    ['8641', 'Sorring'],
    ['8643', 'Ans By'],
    ['8653', 'Them'],
    ['8654', 'Bryrup'],
    ['8660', 'Skanderborg'],
    ['8670', 'Låsby'],
    ['8680', 'Ry'],
    ['8700', 'Horsens'],
    ['8721', 'Daugård'],
    ['8722', 'Hedensted'],
    ['8723', 'Løsning'],
    ['8732', 'Hovedgård'],
    ['8740', 'Brædstrup'],
    ['8751', 'Gedved'],
    ['8752', 'Østbirk'],
    ['8762', 'Flemming'],
    ['8763', 'Rask Mølle'],
    ['8765', 'Klovborg'],
    ['8766', 'Nørre Snede'],
    ['8781', 'Stenderup'],
    ['8783', 'Hornsyld'],
    ['8789', 'Endelave'],
    ['8799', 'Tunø'],
    ['8800', 'Viborg'],
    ['8830', 'Tjele'],
    ['8831', 'Løgstrup'],
    ['8832', 'Skals'],
    ['8840', 'Rødkærsbro'],
    ['8850', 'Bjerringbro'],
    ['8860', 'Ulstrup'],
    ['8870', 'Langå'],
    ['8881', 'Thorsø'],
    ['8882', 'Fårvang'],
    ['8883', 'Gjern'],
    ['8900', 'Randers C'],
    ['8920', 'Randers NV'],
    ['8930', 'Randers NØ'],
    ['8940', 'Randers SV'],
    ['8950', 'Ørsted'],
    ['8960', 'Randers SØ'],
    ['8961', 'Allingåbro'],
    ['8963', 'Auning'],
    ['8970', 'Havndal'],
    ['8981', 'Spentrup'],
    ['8983', 'Gjerlev J'],
    ['8990', 'Fårup'],
    ['9000', 'Aalborg'],
    ['9200', 'Aalborg SV'],
    ['9210', 'Aalborg SØ'],
    ['9220', 'Aalborg Øst'],
    ['9230', 'Svenstrup J'],
    ['9240', 'Nibe'],
    ['9260', 'Gistrup'],
    ['9270', 'Klarup'],
    ['9280', 'Storvorde'],
    ['9293', 'Kongerslev'],
    ['9300', 'Sæby'],
    ['9310', 'Vodskov'],
    ['9320', 'Hjallerup'],
    ['9330', 'Dronninglund'],
    ['9340', 'Asaa'],
    ['9352', 'Dybvad'],
    ['9362', 'Gandrup'],
    ['9370', 'Hals'],
    ['9380', 'Vestbjerg'],
    ['9381', 'Sulsted'],
    ['9382', 'Tylstrup'],
    ['9400', 'Nørresundby'],
    ['9430', 'Vadum'],
    ['9440', 'Aabybro'],
    ['9460', 'Brovst'],
    ['9480', 'Løkken'],
    ['9490', 'Pandrup'],
    ['9492', 'Blokhus'],
    ['9493', 'Saltum'],
    ['9500', 'Hobro'],
    ['9510', 'Arden'],
    ['9530', 'Støvring'],
    ['9541', 'Suldrup'],
    ['9550', 'Mariager'],
    ['9560', 'Hadsund'],
    ['9574', 'Bælum'],
    ['9575', 'Terndrup'],
    ['9600', 'Aars'],
    ['9610', 'Nørager'],
    ['9620', 'Aalestrup'],
    ['9631', 'Gedsted'],
    ['9632', 'Møldrup'],
    ['9640', 'Farsø'],
    ['9670', 'Løgstør'],
    ['9681', 'Ranum'],
    ['9690', 'Fjerritslev'],
    ['9700', 'Brønderslev'],
    ['9740', 'Jerslev J'],
    ['9750', 'Østervrå'],
    ['9760', 'Vrå'],
    ['9800', 'Hjørring'],
    ['9830', 'Tårs'],
    ['9850', 'Hirtshals'],
    ['9870', 'Sindal'],
    ['9881', 'Bindslev'],
    ['9900', 'Frederikshavn'],
    ['9940', 'Læsø'],
    ['9970', 'Strandby'],
    ['9981', 'Jerup'],
    ['9982', 'Ålbæk'],
    ['9990', 'Skagen'],

];

$stmt = $db->prepare("INSERT INTO postal_codes (postal_code_id, City) VALUES (?, ?)");

foreach ($data as $row) {
    $stmt->execute($row);
}



// Prepare the SQL statement
$sql = "INSERT INTO `company_details` (`company_details_id`, `company_name`, `street`, `email`, `phone_number`, `opening_hours`, `postal_code_id`) 
        VALUES (NULL, :company_name, :street, :email, :phone_number, :opening_hours, :postal_code_id)";

// Prepare the statement
$stmt = $db->prepare($sql);

// Bind parameters
$companyName = 'CD Harmony';
$street = 'Melody 1';
$email = 'info@cdharmony.dk';
$phoneNumber = '52333333';
$openingHours = 'Monday - Friday 8:00 - 17:00';
$postalCodeId = '6000';

$stmt->bindParam(':company_name', $companyName);
$stmt->bindParam(':street', $street);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':phone_number', $phoneNumber);
$stmt->bindParam(':opening_hours', $openingHours);
$stmt->bindParam(':postal_code_id', $postalCodeId);

// Execute the statement
$stmt->execute();

   


// Prepare and execute the statement for inserting an admin
function insertUser($db, $first_name, $last_name, $email, $user_password, $creation_date, $role_id)
{
    $stmt = $db->prepare('INSERT INTO users (first_name, last_name, email, user_password, creation_date, role_id) VALUES (:first_name, :last_name, :email, :user_password, :creation_date, :role_id)');

    $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':user_password', $user_password, PDO::PARAM_STR);
    $stmt->bindParam(':creation_date', $creation_date, PDO::PARAM_STR);
    $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);

    $stmt->execute();
}



// Set parameter values for Admin

$setPassAdmin = 'AdminPassword';
$iterationsAdmin = ['cost' => 15];
$HpassAdmin = password_hash($setPassAdmin, PASSWORD_BCRYPT, $iterationsAdmin);
$first_nameAdmin = 'Noga';
$last_nameAdmin = 'Vigdor';
$emailAdmin = 'admin@cdharmony.dk';
$creation_dateAdmin = date('Y-m-d H:i:s');
$role_idAdmin = 1;

insertUser($db, $first_nameAdmin, $last_nameAdmin, $emailAdmin, $HpassAdmin, $creation_dateAdmin, $role_idAdmin);

// Get the last inserted user_id
$user_idAdmin = $db->lastInsertId();

// set parameter values for editor
$setPassEditor = "EditorPassword";
$iterationsEditor = ['cost' => 15];
$HpassEditor = password_hash($setPassEditor, PASSWORD_BCRYPT, $iterationsEditor);
$first_nameEditor = 'Jorgen';
$last_nameEditor = 'Jorgensen';
$emailEditor = 'jorgen@dot.com';
$creation_dateEditor = date('Y-m-d H:i:s');
$role_idEditor = 2;

insertUser($db, $first_nameEditor, $last_nameEditor, $emailEditor, $HpassEditor, $creation_dateEditor, $role_idEditor);


// Insert a customer to users
$setPassCustomer = 'CustomerPassword';
$iterationsCustomer = ['cost' => 15];
$HpassCustomer = password_hash($setPassCustomer, PASSWORD_BCRYPT, $iterationsCustomer);


// Set parameter values
$first_nameCustomer = 'Noga';
$last_nameCustomer = 'Vigdor';
$emailCustomer = 'noga.vigdor@gmail.com';
$creation_dateCustomer = date('Y-m-d H:i:s');
$role_idCustomer = 3; 

insertUser($db, $first_nameCustomer, $last_nameCustomer, $emailCustomer, $HpassCustomer, $creation_dateCustomer, $role_idCustomer);

$customerId = $db->lastInsertId();
//$stmt->debugDumpParams();


// Insert articles

function insertArticle($db,$title, $content, $publish_date, $update_date, $user_id)
{
    // Insert into the 'articles' table
    $stmt = $db->prepare('INSERT INTO articles (title, content, publish_date, update_date, user_id) VALUES (:title, :content, :publish_date, :update_date, :user_id)');

    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':publish_date', $publish_date, PDO::PARAM_STR);
    $stmt->bindParam(':update_date', $update_date, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    $stmt->execute();
}
// Insert articles

insertArticle($db,'"Journey Down the Yellow Brick Road: A Timeless Musical Odyssey with Elton John"',
'As I embarked on a musical exploration through the vast landscape of classic rock, one album stood out like a shimmering beacon – Elton John\'s "Goodbye Yellow Brick Road." This timeless masterpiece, released in 1973, has not only become a cultural landmark but a personal journey into the heart and soul of one of the greatest musical storytellers of our time.

The title track, "Goodbye Yellow Brick Road," serves as a captivating overture to the album. Elton\'s soulful voice, coupled with Bernie Taupin\'s poignant lyrics, creates an enchanting narrative that resonates through time. The metaphorical yellow brick road becomes a metaphor for life\'s journey, and as the music unfolds, it feels like Elton is inviting you to join him on a fantastical expedition.

The album\'s eclectic mix of rock, pop, and ballads showcases Elton John\'s unparalleled versatility as a musician. From the rousing anthem "Saturday Night\'s Alright for Fighting" to the soulful ballad "Candle in the Wind," each track is a brushstroke on the canvas of a musical masterpiece.

One can\'t help but be swept away by the symphonic grandeur of "Funeral for a Friend/Love Lies Bleeding." The seamless transition between these two tracks is a testament to Elton John\'s ability to craft a cohesive musical narrative. It\'s a sonic journey that takes you from the melancholic strains of a funeral to the pulsating beats of love\'s demise.

As the needle glides over the vinyl, the album\'s vintage warmth wraps around you like a comforting embrace. The crackles and pops only add to the authenticity, transporting you to an era where music was an experience, not just a background noise.

Personally, "Goodbye Yellow Brick Road" has been a constant companion during quiet nights and reflective moments. The emotional depth of Elton John\'s music has a way of making you feel understood, no matter where you are on your own yellow brick road of life.

In a world where music often serves as a transient backdrop, Elton John\'s magnum opus stands as a testament to the enduring power of a well-crafted album. "Goodbye Yellow Brick Road" is not just an assortment of songs but a narrative that unfolds with each note, inviting you to explore the complexities of human emotion.

So, if you haven\'t taken this musical journey with Elton John, it\'s time to bid farewell to the ordinary and follow the yellow brick road into a realm where music becomes a timeless companion on the ever-evolving journey of life.',
date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $user_idAdmin);

insertArticle($db,'"Bad" by Michael Jackson: A Timeless Musical Journey',
'Michael Jackson\'s "Bad" album, released in 1987, stands as a testament to his unparalleled musical genius. In this exploration, we unravel the magic that makes "Bad" an enduring classic.

"Bad" isn\'t just an album; it\'s a statement. The title track, with its unforgettable bassline and Jackson\'s signature vocal prowess, became an anthem of self-empowerment. The album\'s revolutionary sound broke down musical barriers and solidified Jackson\'s status as the King of Pop.

Achieving unparalleled success with five consecutive number-one hits on the Billboard Hot 100, "Bad" showcased Jackson\'s ability to craft songs that resonated with audiences worldwide. Hits like "The Way You Make Me Feel" and "Man in the Mirror" remain embedded in the collective memory.

Featuring collaborations with musical heavyweights like Stevie Wonder on "Just Good Friends," "Bad" is a testament to Jackson\'s musical brilliance. The chemistry between these titans added an extra layer of magic to an already stellar album.

The groundbreaking music videos for "Bad" and "Smooth Criminal" showcased Jackson\'s prowess as a performer and a storyteller. His gravity-defying dance moves and cinematic approach set new standards for the industry, breaking down barriers in the world of music videos.

Decades after its release, "Bad" continues to influence artists across genres. Its impact on the music industry and popular culture is immeasurable. "Bad" by Michael Jackson is not just an album; it\'s a cultural phenomenon, a timeless exploration of artistry and innovation that remains etched in the fabric of musical history.',
date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $user_idAdmin);
insertArticle($db,'Abbey Road: A Legendary Sonic Odyssey by The Beatles',
'As I journeyed through the vast landscape of musical history, one album stood out as a defining moment in the evolution of sound – The Beatles\' "Abbey Road." This iconic masterpiece, released in 1969, not only marked the culmination of the Beatles\' illustrious career but left an indelible imprint on the fabric of musical history.

The title track, "Abbey Road," sets the stage for a sonic odyssey like no other. The seamless blend of genres, intricate harmonies, and experimental arrangements showcase the Beatles at the height of their creative prowess. The album\'s progressive structure, with its medley of interconnected songs, transcended the conventional boundaries of popular music.

"Something" and "Come Together" exemplify the diversity within the album. George Harrison\'s soulful ballad "Something" stands as a testament to his songwriting brilliance, while "Come Together" presents John Lennon\'s avant-garde lyricism and showcases the band\'s ability to push artistic boundaries.

The medley, featuring tracks like "You Never Give Me Your Money," "Sun King," and "Golden Slumbers," unfolds like a musical tapestry, weaving together distinct melodies and themes. The famous "Abbey Road" crossing image has become an iconic symbol, representing not just an album cover but a cultural touchstone.

The album\'s production, led by Sir George Martin, introduced groundbreaking techniques and studio innovations. From the lush orchestration in "Something" to the iconic Moog synthesizer in "Because," "Abbey Road" marked a sonic revolution that reverberated across the music industry.

As the final studio album recorded by The Beatles, "Abbey Road" encapsulates the essence of a band pushing the boundaries of creativity and collaboration. The harmonious blend of individual genius contributes to the album\'s timeless appeal, captivating audiences across generations.

Personally, "Abbey Road" has been a source of inspiration and exploration, a musical pilgrimage that transcends time. The album\'s enduring legacy continues to influence artists and listeners alike, reaffirming its status as a pinnacle of artistic achievement.

In a world where musical landscapes continue to evolve, "Abbey Road" remains a touchstone, inviting new generations to experience the magic of a legendary sonic odyssey by The Beatles.',
date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $user_idAdmin);

insertArticle($db,"Let's Dance: A Dance into the Rhythmic World of David Bowie",
'As I ventured into the rhythmic realms of musical exploration, one album emerged as a pulsating invitation to dance – David Bowie\'s "Let\'s Dance." Released in 1983, this iconic album not only marked a shift in Bowie\'s sonic landscape but also became a cultural touchstone for the era.

The title track, "Let\'s Dance," featuring the legendary Stevie Ray Vaughan on guitar, sets the tone for an album that seamlessly blends genres. Bowie\'s smooth vocals, coupled with infectious rhythms, turned the track into a global dance anthem. The album\'s production, helmed by Nile Rodgers, contributed to its polished sound and commercial success.

Tracks like "Modern Love" and "China Girl" showcase Bowie\'s ability to infuse pop sensibilities with lyrical depth. The eclectic mix of styles, from funk to new wave, positions "Let\'s Dance" as a testament to Bowie\'s chameleon-like ability to adapt to and redefine contemporary music.

The rhythmic pulse of "Cat People (Putting Out Fire)" and the introspective vibe of "Without You" further demonstrate Bowie\'s range as a performer and songwriter. The album\'s thematic diversity, coupled with Bowie\'s charismatic presence, made "Let\'s Dance" a cultural phenomenon.

The music videos for the title track and "China Girl," featuring vivid visuals and Bowie\'s iconic dance moves, added a visual dimension to the album\'s impact. The fusion of music and image elevated "Let\'s Dance" beyond a mere collection of songs, making it a multimedia experience.

Personally, "Let\'s Dance" has been a soundtrack to joyous moments and a rhythmic companion during dance-filled nights. Bowie\'s ability to blend sophisticated musicality with accessible hooks creates an album that remains relevant and enjoyable across decades.

In the ever-evolving landscape of music, "Let\'s Dance" stands as a testament to Bowie\'s artistic reinvention and enduring influence. Its legacy continues to inspire generations to embrace the joy of dance and celebrate the boundless creativity of David Bowie.',
date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $user_idAdmin);

insertArticle($db,'The Head on the Door: A Musical Tapestry of The Cure',
'As I delved into the rich tapestry of musical expression, one album emerged as a captivating journey through atmospheric soundscapes – The Cure\'s "The Head on the Door." Released in 1985, this album not only marked a pivotal moment in The Cure\'s discography but also became a timeless exploration of emotional depth and sonic innovation.

The album opens with the infectious and upbeat "In Between Days," setting the stage for a diverse sonic experience. Robert Smith\'s distinctive voice, coupled with the band\'s masterful instrumentation, creates an immersive atmosphere that resonates throughout the album.

"Close to Me," with its catchy hooks and intimate lyrics, showcases The Cure\'s ability to blend pop accessibility with complex emotions. Each track on "The Head on the Door" contributes to a narrative that weaves through themes of love, introspection, and the human experience.

"A Night Like This" and "Push" delve into darker, more atmospheric territories, highlighting The Cure\'s post-punk roots. The juxtaposition of melancholic lyrics with vibrant musical arrangements adds layers of complexity to the listening experience.

The album\'s eclecticism extends to "Kyoto Song," which introduces elements of Eastern influence, demonstrating The Cure\'s willingness to experiment with diverse musical styles. This sonic exploration contributes to the album\'s enduring appeal.

"The Head on the Door" isn\'t merely a collection of songs; it\'s a sonic journey that invites listeners to immerse themselves in the rich textures of The Cure\'s musical landscape. The album\'s impact is not confined to its release era but continues to resonate with new generations of music enthusiasts.

Personally, "The Head on the Door" has been a companion during introspective moments, a testament to the timeless nature of The Cure\'s artistry. The emotional depth and sonic innovation found in this album exemplify why The Cure remains a revered force in the realm of alternative rock.

In the ever-changing landscape of music, "The Head on the Door" stands as a testament to The Cure\'s ability to evolve while staying true to their unique sound. It\'s more than an album; it\'s a captivating chapter in the ongoing story of a band that has left an indelible mark on the world of alternative music.',
date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $user_idAdmin);


// Get the last inserted product_variant_id
//$productVariantId = $db->lastInsertId();

function createSpecialOffer($db, $title, $description, $discountSum, $startDate, $endDate, $productVariantId, $isHomepage) {
    $stmtSpecialOffer = $db->prepare("INSERT INTO special_offers (title, special_offer_description, is_homepage, discount_sum, special_offer_start_date, special_offer_end_date, product_variant_id) VALUES (:title, :special_offer_description, :is_homepage, :discount_sum, :startDate, :endDate, :productVariantId)");

    $stmtSpecialOffer->bindParam(':title', $title, PDO::PARAM_STR);
    $stmtSpecialOffer->bindParam(':special_offer_description', $description, PDO::PARAM_STR);
    $stmtSpecialOffer->bindParam(':is_homepage', $isHomepage, PDO::PARAM_BOOL);
    $stmtSpecialOffer->bindParam(':discount_sum', $discountSum, PDO::PARAM_INT);
    $stmtSpecialOffer->bindParam(':startDate', $startDate, PDO::PARAM_STR);
    $stmtSpecialOffer->bindParam(':endDate', $endDate, PDO::PARAM_STR);
    $stmtSpecialOffer->bindParam(':productVariantId', $productVariantId, PDO::PARAM_INT);

    $stmtSpecialOffer->execute();
}

$productVariantId = $lastInsertedProductVariant;
$specialOffers = [
    [
        'title' => "Exclusive Discount",
        'description' => "Get a special discount on selected items!",
        'discountSum' => 30,
        'startDate' => date('Y-m-d'),
        'endDate' => date('Y-m-d', strtotime('+60 days')),
        'productVariantId' => $productVariantId-342,
        'isHomepage' => 1
    ],
    [
        'title' => "Limited Time Offer",
        'description' => "Hurry up! Limited time offer on popular products!",
        'discountSum' => 20,
        'startDate' => date('Y-m-d'),
        'endDate' => date('Y-m-d', strtotime('+30 days')),
        'productVariantId' => $productVariantId-555,
        'isHomepage' => 0
    ],
    [
        'title' => "Special Deal",
        'description' => "Grab this special deal on new arrivals!",
        'discountSum' => 15,
        'startDate' => date('Y-m-d', strtotime('+10 days')), // Starts 10 days from now
        'endDate' => date('Y-m-d', strtotime('+45 days')),
        'productVariantId' => $productVariantId-780,
        'isHomepage' => 0
    ]
];

foreach ($specialOffers as $offer) {
    createSpecialOffer(
        $db,
        $offer['title'],
        $offer['description'],
        $offer['discountSum'],
        $offer['startDate'],
        $offer['endDate'],
        $offer['productVariantId'],
        $offer['isHomepage']
    );
}


//Insert orders
function insertOrder($db, $creationDate, $orderStatusId, $orderPaymentId, $userId) {
    $stmt = $db->prepare('INSERT INTO orders (creation_date, order_status_id, order_payment_id, user_id) VALUES (:creation_date, :order_status_id, :order_payment_id, :user_id)');

    $stmt->bindParam(':creation_date', $creationDate, PDO::PARAM_STR);
    $stmt->bindParam(':order_status_id', $orderStatusId, PDO::PARAM_INT);
    $stmt->bindParam(':order_payment_id', $orderPaymentId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

    $stmt->execute();
}


//Insert orders lines

function insertOrderLine($db, $quantity, $price, $order_id, $product_variant_id) {
    $stmt = $db->prepare('INSERT INTO orders_lines (quantity, price, order_id, product_variant_id) VALUES (:quantity, :price, :order_id, :product_variant_id)');

    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_variant_id', $product_variant_id, PDO::PARAM_INT);

    $stmt->execute();
}

//first order

$creationDate = date('Y-m-d H:i:s');
$orderStatusId = 3;
$orderPaymentId = 2;
$userId = $customerId;



insertOrder($db, $creationDate, $orderStatusId, $orderPaymentId, $userId);

$orderId1 = $db->lastInsertId();

$quantity = 2;
$price = 97;
$product_variant_id = $lastInsertedProductVariant;

insertOrderLine($db, $quantity, $price, $orderId1, $product_variant_id);

//Insert orders lines
$quantity = 1;
$price = 50;

$product_variant_id = $lastInsertedProductVariant-777;

insertOrderLine($db, $quantity, $price, $orderId1, $product_variant_id);


//second order

$creationDate = date('Y-m-d H:i:s');
$orderStatusId = 3;
$orderPaymentId = 2;
$userId = $customerId;

insertOrder($db, $creationDate, $orderStatusId, $orderPaymentId, $userId);

$orderId2 = $db->lastInsertId();

$quantity = 3;
$price = 79;
$product_variant_id = $lastInsertedProductVariant-32;

insertOrderLine($db, $quantity, $price, $orderId2, $product_variant_id);

$quantity = 1;
$price = 50;
$product_variant_id = $lastInsertedProductVariant-67;

insertOrderLine($db, $quantity, $price, $orderId2, $product_variant_id);

$quantity = 5;
$price = 99;
$product_variant_id = $product_variant_id = $lastInsertedProductVariant;-502;

insertOrderLine($db, $quantity, $price, $orderId2, $product_variant_id);


 // Commit the transaction
 $db->commit();


$dbConnector->closeConnection();

echo "Data inserted successfully!\n";

} catch (PDOException $e) {
    // An error occurred, rollback the transaction
    $db->rollBack();
    die("Error: " . $e->getMessage());
}
