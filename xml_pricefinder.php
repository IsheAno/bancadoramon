<?php 



require_once 'app/Mage.php';

Mage::app();
/* $collection = Mage::getModel('catalog/product')->getCollection()
//->addAttributeToSelect('*') // select all attributes
->setPageSize(2) // limit number of results returned
->setCurPage(1); // set the offset (useful for pagination)*/

$collection = Mage::getModel('catalog/product')->getCollection()
->setStoreId(Mage::app()->getStore()->getId())
->addAttributeToFilter('visibility', 4) // Visible products
->addAttributeToSort('created_at', 'DESC')
->addAttributeToFilter('status', '1')
//->setPageSize(10)
->load();

$xml = new SimpleXMLElement("<loja></loja>");
$storeName = Mage::app()->getStore()->getName();
$url_loja = Mage::app()->getStore()->getHomeUrl();
$xml->addChild('nome_loja',$storeName);
$xml->addChild('qtd_produtos',$collection->count());
$xml->addChild('url_loja',$url_loja);

$produtos = $xml->addChild('produtos');

foreach ($collection as $product) {
	
	$product = Mage::getModel('catalog/product')->load($product->getId());

	$produto = $produtos->addChild('produto');
	$produto->addChild('nome',$product->getName());
	$produto->addChild('preco',(float) $product->getPrice());
	$produto->addChild('preco_desconto',(float) $product->getSpecialPrice());
	$produto->addChild('descricao',htmlspecialchars($product->getDescription()));
	$categorias = $produto->addChild('categorias');
  
  foreach ($product->getCategoryIds() as $category_id) {
      $category = Mage::getModel('catalog/category')->load($category_id);
      $categorias->addChild('categoria',htmlspecialchars($category->getName()));
  }
  
  $produto->addChild('url_produto',$product->getProductUrl());
  $imagens = $produto->addChild('imagens');  
  foreach ($product->getMediaGalleryImages() as $image) {
  	$imagens->addChild('imagem',$image->getUrl());
  }
}
header("Content-type: application/xml");
echo $xml->asXML();