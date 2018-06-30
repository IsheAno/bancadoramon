<?php
/*
 *Gerador de XML específico para o google, no formato rss 2.0
 Mais info em
 http://support.google.com/merchants/bin/answer.py?hl=pt-BR&answer=188494#BR
 http://support.google.com/merchants/bin/answer.py?hl=pt-BR&answer=160589&topic=2473799&ctx=topic
 */

// Log do tipo catch all que guarda todo erro printado na tela
ob_start();

$inicio_exec = microtime();
error_reporting(E_ALL);
ini_set('display_errors',1);



$connectData = simplexml_load_file('../../app/etc/local.xml');

// Setando as variáveis de conexão com o banco
$host = (string) $connectData->global->resources->default_setup->connection->host;
$user = (string) $connectData->global->resources->default_setup->connection->username;
$pass = (string) $connectData->global->resources->default_setup->connection->password;
$base = (string) $connectData->global->resources->default_setup->connection->dbname;

// Conexão com o banco de dados
$connect = mysql_connect($host, $user, $pass, $base) OR die();
mysql_select_db($base, $connect);
mysql_query("SET NAMES 'UTF8'", $connect);

// Inicia o objeto de XML
$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><rss version='2.0' xmlns:g='http://base.google.com/ns/1.0' xmlns:c='http://base.google.com/cns/1.0'></rss>");
$ns = (object) array('g'=>'http://base.google.com/ns/1.0');
$xml->registerXPathNamespace('g', 'http://base.google.com/ns/1.0');

//$xml = new SimpleXMLElement(""); //comentar esta linha pra ficar como rss



$pData = $xml->addChild('channel');
$pData->addChild('title','Banca Do Ramon');
$pData->addChild('link','http://www.bancadoramon.com.br/');
addCdata($pData->addChild('description'),'Banca do Ramon, desde 1993 presente na casa da família brasileira');

// Função simples para retorno de queries em formato de array
function exec_query($query) {
    global $connect;
    $result = array();
    $resource = mysql_query($query, $connect);
    while ($row = mysql_fetch_assoc($resource)) {
        $result[] = $row;
    }
    return $result;
}

function addCData($obj, $cdata_text) {
    $node= dom_import_simplexml($obj);
    $no = $node->ownerDocument;
    $node->appendChild($no->createCDATASection(utf8_encode($cdata_text)));
}

// Recupera as informações de urls
$query = "SELECT path, value FROM core_config_data WHERE path IN ('web/unsecure/base_url', 'web/unsecure/base_media_url') ORDER BY path DESC;";


$arrCore = exec_query($query);
foreach ($arrCore AS $val) {
    $path = preg_replace("/web\/unsecure\//", "", $val['path']);
    if ($val['value'] == '{{base_url}}') {
        $value = "http://bancadoramon.lojaemteste.com.br/";
    } else if (preg_match("/{{unsecure_base_url}}/", $val['value'])) {
        $value = preg_replace("/{{unsecure_base_url}}/", $coreData['base_url'], $val['value']);
    } else {
        $value = $val['value'];
    }
    $coreData[$path] = 'http://www.bancadoramon.com.br/';
}
$coreData['base_url'] = 'http://www.bancadoramon.com.br/';
$coreData['base_media_url'] = 'http://www.bancadoramon.com.br/media/';

//$query = "SELECT category_ids FROM nostress_export WHERE searchengine = 'clickaporter' AND enabled = 1;";
//$arrCats = exec_query($query);
//$categories = '';
//if (is_array($arrCats) && (count($arrCats) > 0 && (isset($arrCats[0]['category_ids']) && (strlen(trim($arrCats[0]['category_ids'])) > 0)))) {
//  $categories = trim($arrCats[0]['category_ids']);
//}

// Busca dos produtos seguindo os seguintes critérios: configurável, com simples atribuído, com estoque e nas categorias específicas

//$query = "SELECT DISTINCT conf.entity_id FROM catalog_category_product AS cat INNER JOIN catalog_product_entity AS conf INNER JOIN catalog_product_relation AS rel ON (rel.parent_id = conf.entity_id) INNER JOIN cataloginventory_stock_item AS stk ON (stk.product_id = rel.child_id AND qty > 0) WHERE conf.type_id = 'configurable' Group by entity_id;";

//Recupera valor que
$atribQuery = "Select attribute_id from eav_attribute where attribute_code = 'status' AND backend_type = 'int';";
$atrib = exec_query($atribQuery);

foreach ($atrib AS $return) {
    $atributo = $return['attribute_id'];
}


$query = "SELECT DISTINCT conf.entity_id FROM catalog_product_entity AS conf ";
$query .= "INNER JOIN cataloginventory_stock_item AS stk ";
$query .= "INNER JOIN catalog_product_entity_int AS b ";
$query .= "ON stk.qty > 0 AND conf.entity_id=stk.product_id AND b.entity_id=conf.entity_id AND b.attribute_id=" . $atributo . " AND b.`VALUE`=1;";

$products = exec_query($query);
$query = null;

// Inicia interações nos produtos selecionados para popular o objeto XML;
$count = 0;
foreach ($products AS $product) {
    $count++;
    $product_id = $product['entity_id'];

    // Recupera as informações do produto configurável
    $query = "SELECT ";
    $query.= "  ent.entity_id, ";
    $query.= "  ent.sku, ";
    $query.= "  url.value AS url, ";
    $query.= "  name.value AS nome, ";
    $query.= "  dsc.value AS descricao, ";
    $query.= "  stk.qty AS stock, ";
    $query.= "  stk.is_in_stock AS is_in_stock, ";
    $query.= "  'bancadoramon' AS marca, ";
    $query.= "  price.price AS preco_de, ";
    $query.= "  CASE WHEN ( ";
    $query.= "      (sfr.value < NOW() AND (sto.value > NOW() OR sto.value IS NULL)) OR ";
    $query.= "      (sfr.value IS NULL AND sto.value > NOW()) OR ";
    $query.= "      (sfr.value IS NULL AND sto.value IS NULL) ";
    $query.= "    ) THEN price.final_price ";
    $query.= "    ELSE NULL ";
    $query.= "  END AS preco_por, ";
    $query.= "  img.value AS imagem ";
    $query.= "FROM ";
    $query.= "  catalog_product_entity AS ent ";
    $query.= "  LEFT JOIN catalog_product_entity_varchar AS url ON ";
    $query.= "    (";
    $query.= "    url.entity_id = ent.entity_id AND ";
    $query.= "    url.attribute_id = (SELECT attribute_id FROM eav_attribute WHERE entity_type_id = 4 AND attribute_code = 'url_path') AND ";
    $query.= "    url.store_id = 0";
    $query.= "    ) ";
    $query.= "  LEFT JOIN catalog_product_entity_varchar AS name ON  ";
    $query.= "    ( ";
    $query.= "    name.entity_id = ent.entity_id AND  ";
    $query.= "    name.attribute_id = (SELECT attribute_id FROM eav_attribute WHERE entity_type_id = 4 AND attribute_code = 'name') AND  ";
    $query.= "    name.store_id = 0 ";
    $query.= "    ) ";
    $query.= "  LEFT JOIN catalog_product_entity_text AS dsc ON  ";
    $query.= "    ( ";
    $query.= "    dsc.entity_id = ent.entity_id AND  ";
    $query.= "    dsc.attribute_id = (SELECT attribute_id FROM eav_attribute WHERE entity_type_id = 4 AND attribute_code = 'description') AND  ";
    $query.= "    dsc.store_id = 0 ";
    $query.= "    ) ";
    $query.= "  LEFT JOIN catalog_product_entity_varchar AS img ON  ";
    $query.= "    (";
    $query.= "    img.entity_id = ent.entity_id AND ";
    $query.= "    img.attribute_id = (SELECT attribute_id FROM eav_attribute WHERE entity_type_id = 4 AND attribute_code = 'image') AND ";
    $query.= "    img.store_id = 0";
    $query.= "    ) ";
    $query.= "  LEFT JOIN catalog_product_entity_datetime AS sfr ON ";
    $query.= "    ( ";
    $query.= "     sfr.entity_id = ent.entity_id AND ";
    $query.= "     sfr.attribute_id = (SELECT attribute_id FROM eav_attribute WHERE entity_type_id = 4 AND attribute_code = 'special_from_date') AND ";
    $query.= "     sfr.store_id = 0 ";
    $query.= "     ) ";
    $query.= "  LEFT JOIN catalog_product_entity_datetime AS sto ON ";
    $query.= "    ( ";
    $query.= "    sto.entity_id = ent.entity_id AND ";
    $query.= "    sto.attribute_id = (SELECT attribute_id FROM eav_attribute WHERE entity_type_id = 4 AND attribute_code = 'special_to_date') AND ";
    $query.= "    sto.store_id = 0 ";
    $query.= "    ) ";
    $query.= "  LEFT JOIN catalog_product_index_price AS price ON ";
    $query.= "    (";
    $query.= "    price.entity_id = ent.entity_id AND ";
    $query.= "    price.customer_group_id = 1";
    $query.= "    ) ";
    $query.= "  LEFT JOIN cataloginventory_stock_item AS stk ON ";
    $query.= "    (";
    $query.= "    stk.product_id = ent.entity_id ";
    $query.= "    ) ";
    $query.= "WHERE ent.entity_id = $product_id;";

    $entityData = current(exec_query($query));

    // Recupera as informações de categorias
    $query = "SELECT cat.value AS categoria ";
    $query.= "FROM catalog_category_product AS rel ";
    $query.= "  INNER JOIN catalog_category_entity AS ct ON (ct.entity_id = rel.category_id) ";
    $query.= "  INNER JOIN catalog_category_entity_varchar AS cat ON (cat.entity_id = ct.entity_id AND attribute_id = (SELECT attribute_id FROM eav_attribute WHERE entity_type_id = 3 AND attribute_code = 'name')) ";
    $query.= "WHERE rel.product_id = $product_id";

    $categories = exec_query($query);

    $cats = array();
    foreach ($categories AS $category) {
        $cats[] = $category['categoria'];
    }
    $strCategoria = implode(' > ', $cats);

    // Recupera as informações relativas aos produtos simples
    $query = "SELECT cor.value AS color, img.value AS image, tam.value AS size ";
    $query.= "FROM ";
    $query.= "  catalog_product_entity_media_gallery AS img ";
    $query.= "  INNER JOIN catalog_product_relation AS rel ON (rel.parent_id = img.entity_id) ";
    $query.= "  INNER JOIN cataloginventory_stock_item AS stk ON (stk.product_id = rel.child_id and qty > 0) ";
    $query.= "  INNER JOIN catalog_product_entity_int AS opt ON ";
    $query.= "    ( ";
    $query.= "    opt.entity_id = rel.child_id AND ";
    $query.= "    opt.attribute_id = (SELECT attribute_id FROM eav_attribute WHERE entity_type_id = 4 AND attribute_code = 'color') AND ";
    $query.= "    opt.store_id = 0 ";
    $query.= "    ) ";
    $query.= "  INNER JOIN eav_attribute_option_value AS val ON (val.option_id = opt.value AND val.store_id = 0) ";
    $query.= "  INNER JOIN eav_attribute_option_value AS cor ON (cor.option_id = opt.value AND cor.store_id = 1) ";
    $query.= "  INNER JOIN catalog_product_entity_int AS cpi ON ";
    $query.= "    ( ";
    $query.= "    cpi.entity_id = rel.child_id AND ";
    $query.= "    cpi.attribute_id = (SELECT attribute_id FROM eav_attribute WHERE entity_type_id = 4 AND attribute_code = 'size') AND ";
    $query.= "    cpi.store_id = 0 ";
    $query.= "    ) ";
    $query.= "  INNER JOIN eav_attribute_option_value AS tam ON (tam.option_id = cpi.value AND tam.store_id = 0) ";
    $query.= "WHERE ";
    $query.= "  img.entity_id = $product_id ";
    $query.= "  AND img.value LIKE CONCAT('%',TRIM(val.value),'%');";

    $multiple = exec_query($query);

    $colors = $images = $sizes = Array();
    foreach ($multiple AS $each) {
        $colors[] = $each['color'];
        $images[] = $each['image'];
        $sizes[] = $each['size'];
    }

    // Monta efetivamente o XML
    $prodNode = $pData->addChild('item');

    addCdata($prodNode->addChild('title'), ucfirst(strtolower(utf8_decode(html_entity_decode($entityData['nome'])))));
    $prodNode->addChild('link', $coreData['base_url'] . trim($entityData['url']));
    addCdata($prodNode->addChild('description'), trim(utf8_decode(html_entity_decode(strip_tags($entityData['descricao'])))));
    $prodNode->addChild('g:id', trim($entityData['sku']), $ns->g);
    $prodNode->addChild('g:condition', 'NEW', $ns->g);

    if($entityData['preco_por']){
        $prodNode->addChild('g:price', number_format($entityData['preco_por'], 2, '.', ''), $ns->g);
    }else{
        $prodNode->addChild('g:price', number_format($entityData['preco_de'], 2, '.', ''), $ns->g);

    }


    if( $entityData['is_in_stock'] == 1 ) {
        $prodNode->addChild('g:availability', 'in stock', $ns->g);
    } else {
        $prodNode->addChild('g:availability', 'out of stock', $ns->g);
    }
    $prodNode->addChild('g:image_link', $coreData['base_media_url'] . 'catalog/product' . trim($entityData['imagem']), $ns->g);
    $shipping = $prodNode->addChild('g:shipping', 'in stock',$ns->g);
    $shipping->addChild('g:country','BR', $ns->g);
    $shipping->addChild('g:service','Correios', $ns->g);
    $shipping->addChild('g:instock', $entityData['stk.is_in_stock'], $ns->g);
    //se nao estiver em Vestuario e acessórios, o comportamento da validação do google mudará bastante
    // addCdata($prodNode->addChild('CODIGO'), trim($entityData['entity_id']));
    // addCdata($prodNode->addChild('marca'), trim($entityData['marca']));
    // addCdata($prodNode->addChild('DEPARTAMENTO'), trim($strCategoria));
    // addCdata($prodNode->addChild('SUBDEPARTAMENTO'), trim($strSubCategoria));
    // addCdata($prodNode->addChild('PRECO_DE'), number_format($entityData['preco_de'], 2, ',', ''));

    // Calculo de parcelas sem juros (com base no do terra)
    $preco = ((float) $entityData['preco_por'] > 0 ? (float) $entityData['preco_por'] : (float) $entityData['preco_de']);
    $parc = false;

    if($parc){
        $parcelas = $prodNode->addChild('g:installment', $ns->g);
        $parcelas->addChild('g:months',$qtd_parcelas, $ns->g);
        $parcelas->addChild('g:amount', 'R$ '. $vlr_parcela, $ns->g);
    }

    if (is_array($images) && (count($images) > 0)) {
        // $imagens = $prodNode->addChild('imagens');
        foreach (array_unique($images) AS $image) {
            $prodNode->addChild('g:additional_image_link', $coreData['base_media_url'] . 'catalog/product' . trim($image), $ns->g);
        }
    }

    if (is_array($colors) && (count($colors) > 0)) {
        // $cores = $prodNode->addChild('cores');
        $cores = array();
        foreach (array_unique($colors) AS $color) {
            // addCdata($cores->addChild('cor'), trim($color));
            $cores[] = ucfirst(strtolower($color));
        }
        $prodNode->addChild('g:color',implode(',', $cores), $ns->g);
    }
    $prodNode->addChild('g:google_product_category', 'Vestuário e acessórios > ' . html_entity_decode($strCategoria), $ns->g);
    $prodNode->addChild('g:product_type','Vestuário e acessórios >',$ns->g);
    $prodNode->addChild('g:brand','bancadoramon', $ns->g);
    $prodNode->addChild('g:mpn',trim($entityData['sku']), $ns->g);
    // if (is_array($sizes) && (count($sizes) > 0)) {
    //   $tamanhos = $prodNode->addChild('tamanhos');
    //   foreach (array_unique($sizes) AS $size) {
    //     addCdata($tamanhos->addChild('tamanho'), trim($size));
    //   }
    // }



}

// Recuperação de qualquer tipo de output que o script gerou
$buffer = ob_get_clean();

ob_start();
if (strlen($buffer) == 0) {
    $buffer = "[" . date("Y-m-d H:i:s") . "] Script executado com sucesso. gerado XML para $count produtos configuráveis em " . number_format((microtime() - $inicio_exec),3) . "s.\n";
} else {
    $mark = microtime();
    $buffer = "[".date("Y-m-d H:i:s")."] Foram encontrados os seguintes erros ao gerar o script: \nINICIO DO LOG $mark >>\n" . $buffer . "\n>> FIM do log $mark\n)\n";
}

// Escreve o output no arquivo de log
//$logdir = dirname(dirname(__FILE__)) . '../../var/log/parceiros';
$logdir = dirname(dirname(__FILE__)) . '/log';
if (!is_dir($logdir)) mkdir($logdir, 777, true);
$file = fopen($logdir . '/sitemapbancadoramon.log', 'a+');
fwrite($file, $buffer, strlen($buffer));
fclose($file);
ob_end_clean();

// Printa o arquivo XML
if (!headers_sent()) header('content-type: text/xml; charset=UTF-8');
echo $xml->asXML();

//Grava o XML gerado no arquivo de integracao
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());

$filedir = "../../sitemap";
$dom->save("$filedir/google.xml");