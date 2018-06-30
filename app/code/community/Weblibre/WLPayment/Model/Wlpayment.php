<?php
class Weblibre_WLPayment_Model_WLPayment extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('wlpayment/wlpayment');
    }
	
	public function saveReg($id, $transId = '', $parcelas = 0, $transValor = 0, $tipoPagto = '', $transResposta = -1, $transMensagem = '', $operadora = '', $operadoraMensagem = '', $transId2 = '', $parcelas2 = 0, $transValor2 = 0, $tipoPagto2 = '', $transResposta2 = -1, $transMensagem2 = '', $operadora2 = '', $operadoraMensagem2 = '', $authId = '', $authTipo = 0, $numeroCartao = '', $bancoEmissor = '', $numAutor = 0, $numSQN = 0, $numCV = 0) {
		if (empty($id)) {  //isso assegura que se o valor de $id for uma string vazia ent�o ser� -1
			return(-1);
		}
		
		if (empty($transResposta)) { //isso assegura que se o valor de $transResposta for uma string vazia ent�o ser� 0
			$transResposta = 0;
		}
		
		if (empty($authTipo)) { //isso assegura que se o valor de $authTipo for uma string vazia ent�o ser� 0
			$authTipo = 0;
		}
		
		if ($this->exists($id)) {
			return($this->update($id, $transId, $parcelas, $transValor, $tipoPagto, $transResposta, $transMensagem, $operadora, $operadoraMensagem, $transId2, $parcelas2, $transValor2, $tipoPagto2, $transResposta2, $transMensagem2, $operadora2, $operadoraMensagem2, $authId, $authTipo, $numeroCartao, $bancoEmissor, $numAutor, $numSQN, $numCV));
		}
		else {
			return($this->insert($id, $transId, $parcelas, $transValor, $tipoPagto, $transResposta, $transMensagem, $operadora, $operadoraMensagem, $transId2, $parcelas2, $transValor2, $tipoPagto2, $transResposta2, $transMensagem2, $operadora2, $operadoraMensagem2, $authId, $authTipo, $numeroCartao, $bancoEmissor, $numAutor, $numSQN, $numCV));
		}
	}
    
    public function saveParcelamento($idTransacao = '', $pedido = 0, $vencimento = '', $pagamento = '', $valor = 0, $valorPago = 0, $transTipo = '', $transResposta = '',$transMensagem = '', $parcela = 0, $conta = 0) {
		if (empty($pedido) || empty($parcela)) {
			return(-1);
		}
		
		if (empty($conta)) { 
			$conta = 0;
		}
		
		if (empty($valor)) { 
			$valor = 0;
		}
		
		if ($this->existsParcela($pedido,$parcela)) {
			return($this->updateParcelamento($idTransacao, $pedido, $vencimento, $pagamento, $valor, $valorPago, $transTipo, $transResposta,$transMensagem, $parcela, $conta));
		}
		else {
			return($this->insertParcelamento($idTransacao, $pedido, $vencimento, $pagamento, $valor, $valorPago, $transTipo, $transResposta,$transMensagem, $parcela, $conta));
		}
	}
	
    public function saveRetorno($pedido = 0, $dataPedido = '', $dataRetorno = '', $metodo, $statusPedido, $transResposta, $transMensagem) {
        return(insertRetorno($pedido, $dataPedido, $dataRetorno, $metodo, $statusPedido, $transResposta, $transMensagem));
    }
    
	public function exists($id) {
		if (empty($id)) {
			return false;
		}
	
		if($this->getDebug()) {
			$writer = new Zend_Log_Writer_Stream($this->getLogPath());
			$logger = new Zend_Log($writer);
			$logger->info('entrou em WlPayment - exists');
		}

		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$tableName = Mage::getSingleton('core/resource')->getTableName('wlpayment');

		$sql = "select id from $tableName where id = $id";
		
		if($this->getDebug()) {
			$logger->info($sql);
		}

		$result = $w->query($sql);

		if (!$result) {
			return false;
		}

		$row = $result->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			return false;
		}
		else {
			return true;
		}
	}
	
	public function existsParcela($pedido,$parcela) {
		if (empty($pedido) || empty($parcela)) {
			return(-1);
		}
	
		if($this->getDebug()) {
			$writer = new Zend_Log_Writer_Stream($this->getLogPath());
			$logger = new Zend_Log($writer);
			$logger->info('entrou em WlPayment - existsParcela');
		}

		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$tableName = Mage::getSingleton('core/resource')->getTableName('wlparcelamento');

		$sql = "select id from $tableName where pedido = $pedido AND parcela = $parcela";
		
		if($this->getDebug()) {
			$logger->info($sql);
		}

		$result = $w->query($sql);

		if (!$result) {
			return false;
		}

		$row = $result->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			return false;
		}
		else {
			return true;
		}
	}
	
	public function insert($id, $transId = '', $parcelas, $transValor = 0, $tipoPagto = '', $transResposta = -1, $transMensagem = '', $operadora = '', $operadoraMensagem = '', $transId2 = '', $parcelas2, $transValor2 = 0, $tipoPagto2 = '', $transResposta2 = -1, $transMensagem2 = '', $operadora2 = '', $operadoraMensagem2 = '',$authId = '', $authTipo = 0, $numeroCartao = '', $bancoEmissor = '', $numAutor = 0, $numSQN = 0, $numCV = 0) {
		$ip = $_SERVER['REMOTE_ADDR'];
		
		if (empty($parcelas) || $parcelas <= 0) {
			$parcelas = 1;
		}
		
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$tableName = Mage::getSingleton('core/resource')->getTableName('wlpayment');
		
		$sql = "insert into $tableName ";
		$sql .= "(";
		$sql .= "id, ";
		$sql .= "parcelas, ";
		$sql .= "transId, ";
		$sql .= "transResposta, ";
		$sql .= "transValor, ";
		$sql .= "tipoPagto, ";
		$sql .= "transMensagem, ";
        $sql .= "operadora, ";
        $sql .= "operadoraMensagem, ";
		$sql .= "parcelas2, ";
		$sql .= "transId2, ";
		$sql .= "transResposta2, ";
		$sql .= "transValor2, ";
		$sql .= "tipoPagto2, ";
		$sql .= "transMensagem2, ";
        $sql .= "operadora2, ";
        $sql .= "operadoraMensagem2, ";
        $sql .= "authId, ";
		$sql .= "authTipo, ";
		$sql .= "authValor, ";
		$sql .= "numeroCartao, ";
		$sql .= "bancoEmissor, ";
		$sql .= "autorizacaoId, ";
		$sql .= "numSeqUnico, ";
		$sql .= "numCompVenda, ";
		$sql .= "created_time, ";
		$sql .= "updated_time, ";
		$sql .= "ip ";
		$sql .= ") ";
		$sql .= "values (";
		$sql .= "$id, ";
		$sql .= "$parcelas, ";
		$sql .= "'$transId', ";
		$sql .= "$transResposta, ";
		$sql .= "$transValor, ";
		$sql .= "'$tipoPagto', ";
		$sql .= "'$transMensagem', ";
        $sql .= "'$operadora', ";
        $sql .= "'$operadoraMensagem', ";
		$sql .= "$parcelas2, ";
		$sql .= "'$transId2', ";
		$sql .= "$transResposta2, ";
		$sql .= "$transValor2, ";
		$sql .= "'$tipoPagto2', ";
		$sql .= "'$transMensagem2', ";        
        $sql .= "'$operadora2', ";   
        $sql .= "'$operadoraMensagem2', ";   
		$sql .= "'$authId', ";
		$sql .= "$authTipo, ";
		$sql .= "$transValor, ";
		$sql .= "'$numeroCartao', ";
		$sql .= "'$bancoEmissor', ";
		$sql .= "$numAutor, ";
		$sql .= "$numSQN, ";
		$sql .= "$numCV, ";
		$sql .= "NOW(), ";
		$sql .= "NOW(), ";
		$sql .= "'$ip'";
		$sql .= ")";
		
		if($this->getDebug()) {
			$writer = new Zend_Log_Writer_Stream($this->getLogPath());
			$logger = new Zend_Log($writer);
			$logger->info('WL Payment - Insert - SQL: ' . $sql);
		}

		$result = $w->query($sql);

		if (!$result) {
			return false;
		}
		else {
			return true;
		}
	}

	public function update($id, $transId = '', $parcelas =0, $transValor = 0, $tipoPagto = '', $transResposta = -1, $transMensagem = '', $operadora = '', $operadoraMensagem = '', $transId2 = '', $parcelas2 =0, $transValor2 = 0, $tipoPagto2 = '', $transResposta2 = -1, $transMensagem2 = '', $operadora2 = '', $operadoraMensagem2 = '', $authId = '', $authTipo = 0, $numeroCartao = '', $bancoEmissor = '', $numAutor = 0, $numSQN = 0, $numCV = 0) {
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$tableName = Mage::getSingleton('core/resource')->getTableName('wlpayment');

		$sql = "update $tableName set ";
		if ($parcelas > 0) {
			$sql .= "parcelas = $parcelas, ";
		}
		if (!empty($transId)) {
			$sql .= "transId = '$transId', ";
		}
		$sql .= "transResposta = $transResposta, ";
		if ($transValor > 0) {
			$sql .= "transValor = $transValor, ";
		}
		if ($tipoPagto != '') {
			$sql .= "tipoPagto = '$tipoPagto', ";
		}
		if ($transMensagem != '') {
			$sql .= "transMensagem = '".addslashes(str_replace("'", "", $transMensagem))."', ";
		}
		if ($operadora != '') {
			$sql .= "operadora = '".addslashes(str_replace("'", "", $operadora))."', ";
		}          
		if ($operadoraMensagem != '') {
			$sql .= "operadoraMensagem = '".addslashes(str_replace("'", "", $operadoraMensagem))."', ";
		}        
		if ($parcelas2 > 0) {
			$sql .= "parcelas2 = $parcelas2, ";
		}
		if (!empty($transId2)) {
			$sql .= "transId2 = '$transId2', ";
		}
		$sql .= "transResposta2 = $transResposta2, ";
		if ($transValor2 > 0) {
			$sql .= "transValor2 = $transValor2, ";
		}
		if ($tipoPagto2 != '') {
			$sql .= "tipoPagto2 = '$tipoPagto2', ";
		}
		if ($transMensagem2 != '') {
			$sql .= "transMensagem2 = '".addslashes(str_replace("'", "", $transMensagem2))."', ";
		}
		if ($operadora2 != '') {
			$sql .= "operadora2 = '".addslashes(str_replace("'", "", $operadora2))."', ";
		}          
		if ($operadoraMensagem2 != '') {
			$sql .= "operadoraMensagem2 = '".addslashes(str_replace("'", "", $operadoraMensagem2))."', ";
		}        
		if (!empty($authId)) {
			$sql .= "authId = '$authId', ";
		}
		if ($authTipo != 0) {
			$sql .= "authTipo = $authTipo, ";
		}
		if ($transValor > 0) {
			$sql .= "authValor = $transValor, ";
		}
		if ($numeroCartao != '') {
			$sql .= "numeroCartao = '$numeroCartao', ";
		}
		if ($bancoEmissor != '') {
			$sql .= "bancoEmissor = '$bancoEmissor', ";
		}
		if ($numAutor != 0) {
			$sql .= "autorizacaoId = $numAutor, ";
		}
		if ($numSQN != 0) {
			$sql .= "numSeqUnico = $numSQN, ";
		}
		if ($numCV != 0) {
			$sql .= "numCompVenda = $numCV, ";
		}
		$sql .= " updated_time = NOW(), ";
		$sql .= " ip = '$ip'";
		$sql .= " Where id = $id";
		
		if($this->getDebug()) {
			$writer = new Zend_Log_Writer_Stream($this->getLogPath());
			$logger = new Zend_Log($writer);
			$logger->info('WL Payment - Update - SQL: ' . $sql);
		}

		$result = $w->query($sql);

		if (!$result) {
			return false;
		}
		else {
			return true;
		}
	}
	
    public function insertParcelamento($idTransacao, $pedido, $vencimento, $pagamento, $valor, $valorPago, $transTipo, $transResposta, $transMensagem, $parcela, $conta) {
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$tableName = Mage::getSingleton('core/resource')->getTableName('wlparcelamento');
		
		$sql = "insert into $tableName ";
		$sql .= "(";
		$sql .= "createdData, ";
		$sql .= "updatedData, ";
        $sql .= "idTransacao, ";
		$sql .= "pedido, ";
		$sql .= "vencimento, ";
        $sql .= "pagamento, ";
		$sql .= "valor, ";
		$sql .= "valorPago, ";        
		$sql .= "transTipo, ";
		$sql .= "transResposta, ";
		$sql .= "transMensagem, ";
		$sql .= "parcela, ";
        $sql .= "conta, ";
		$sql .= "ip ";
		$sql .= ") ";
		$sql .= "values (";
        $sql .= "NOW(), ";
        $sql .= "NOW(), ";
		$sql .= "'$idTransacao', ";
		$sql .= "$pedido, ";   
		$sql .= "'$vencimento', ";   
		$sql .= "'$pagamento', ";   
		$sql .= "$valor, ";
		$sql .= "$valorPago, ";        
		$sql .= "'$transTipo', ";
		$sql .= "$transResposta, ";
		$sql .= "'$transMensagem', ";
		$sql .= "$parcela, ";
        $sql .= "$conta, ";
		$sql .= "'$ip'";
		$sql .= ")";
		
		if($this->getDebug()) {
			$writer = new Zend_Log_Writer_Stream($this->getLogPath());
			$logger = new Zend_Log($writer);
			$logger->info('WL Payment - Insert (wlparcelamento) - SQL: ' . $sql);
		}

		$result = $w->query($sql);

		if (!$result) {
			return false;
		}
		else {
			return true;
		}
    }

	public function updateParcelamento($idTransacao, $pedido, $vencimento, $pagamento, $valor, $valorPago, $transTipo, $transResposta, $transMensagem, $parcela, $conta) {
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$tableName = Mage::getSingleton('core/resource')->getTableName('wlparcelamento');

		$sql = "update $tableName set ";
		if (!empty($idTransacao)) {
			$sql .= "idTransacao = '$idTransacao', ";
		}
		$sql .= "transResposta = $transResposta, ";
        $sql .= "pedido = '$pedido', ";
        
        if($conta > 0) {
            $sql .= "conta = $conta, ";
        }
        
        if(is_numeric($valor)) {
            $sql .= "valor = $valor, ";
        }
        
        if(is_numeric($valorPago)) {
            $sql .= "valorPago = $valorPago, ";
        }
        
    	$sql .= "vencimento = '$vencimento', ";
    	$sql .= "pagamento = '$pagamento', ";

		if ($transMensagem != '') {
			$sql .= "transMensagem = '".addslashes(str_replace("'", "", $transMensagem))."', ";
		}
		if ($transTipo != '') {
			$sql .= "transTipo = '".addslashes(str_replace("'", "", $transTipo))."', ";
		}          

		$sql .= " updatedData = NOW(), ";
		$sql .= " ip = '$ip'";
		$sql .= " Where pedido = $pedido AND parcela = $parcela";
		
		if($this->getDebug()) {
			$writer = new Zend_Log_Writer_Stream($this->getLogPath());
			$logger = new Zend_Log($writer);
			$logger->info('WL Payment - Update - SQL: ' . $sql);
		}

		$result = $w->query($sql);

		if (!$result) {
			return false;
		}
		else {
			return true;
		}
	}    
    
    public function insertRetorno($pedido, $dataPedido, $dataRetorno, $metodo, $statusPedido, $transResposta, $transMensagem) {
		
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$tableName = Mage::getSingleton('core/resource')->getTableName('wlretorno');
		
		$sql = "insert into $tableName ";
		$sql .= "(";
		$sql .= "pedido, ";
		$sql .= "dataPedido, ";
        $sql .= "dataRetorno, ";
		$sql .= "metodo, ";
		$sql .= "statusPedido, ";
        $sql .= "transResposta, ";
		$sql .= "transMensagem ";
		$sql .= ") ";
		$sql .= "values (";
		$sql .= "'$pedido', ";
		$sql .= "$dataPedido, ";   
		$sql .= "'$dataRetorno', ";   
		$sql .= "'$metodo', ";   
		$sql .= "$statusPedido, ";
		$sql .= "$transResposta, ";
		$sql .= "'$transMensagem' ";
		$sql .= ")";
		
		if($this->getDebug()) {
			$writer = new Zend_Log_Writer_Stream($this->getLogPath());
			$logger = new Zend_Log($writer);
			$logger->info('WL Payment - Insert (wlretorno) - SQL: ' . $sql);
		}

		$result = $w->query($sql);

		if (!$result) {
			return false;
		}
		else {
			return true;
		}
    }
    
	public function getReg($id) {
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$tableName = Mage::getSingleton('core/resource')->getTableName('wlpayment');

		$sql = "select * from " . $tableName;
		$sql .= " where id = $id";
		
		$result = $w->query($sql);

		if (!$result) {
			return false;
		}

		$row = $result->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			return false;
		}
		else {
			return $row;
		}
	}
    
	public function getParcelamento($pedido) {
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$tableName = Mage::getSingleton('core/resource')->getTableName('wlparcelamento');

		$sql = "select * from " . $tableName;
		$sql .= " where pedido = $pedido";
		
		$result = $w->query($sql);

		if (!$result) {
			return false;
		}
        
        $rows = array();
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$rows[] = $row;
		}
        return $rows;
	}    
	
	public function getDebug() {
		return true;
	}
	
	public function getLogPath() {
		$dir = Mage::getBaseDir() . '/var/log';
		
		if (!file_exists($dir)) {
			mkdir($dir);
		}
		
		return $dir . '/wlpayment.log';
	}
	
	public function geraFatura($order, $tid) {
		if($order->canInvoice()) {
            if($this->getDebug()) {
                $writer = new Zend_Log_Writer_Stream($this->getLogPath());
                $logger = new Zend_Log($writer);
                $logger->info('WLPayment - geraFatura - Order Id: ' . $order->id . ' - Num. Transacao: ' . $tid);
            }

            //need to save transaction id
            $order->getPayment()->setTransactionId($tid);

            //need to convert from order into invoice
            if($this->getDebug()) {
                $logger->info('$order->prepareInvoice()');
            }

            $invoice = $order->prepareInvoice();

            /*if (!$invoice->getTotalQty()) { //esta verificação não funciona no Magento 1.3
                $notify = false;
                $msg = 'Não é possível gerar a fatura automaticamente pois não há produtos.';

                $order->addStatusToHistory($order->getStatus(), $msg, $notify);
                $order->save();
				
				throw (Exception);
			}*/
			
			$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
			$invoice->register();

			//capture
			/*if($this->getDebug()) {
				$logger->info('$invoice->register()->capture()');
			}*/
			//$invoice->capture(); usar apenas quando a captura estiver implementada

			//save
			if($this->getDebug()) {
				$logger->info('addObject->save()');
			}
			Mage::getModel('core/resource_transaction')
			   ->addObject($invoice)
			   ->addObject($invoice->getOrder())
			   ->save();

			$notify = true;
			$msg = 'Fatura ' . $invoice->getIncrementId() . ' foi criada com sucesso.';
			$msg .= ' - fatura gerada automaticamente após autorização da Operadora.';

			//muda o status de acordo com o que foi configurado no Admin
			$order->setState(
				Mage_Sales_Model_Order::STATE_PROCESSING,
				'processing',
				$msg,
				$notify
			);

			//registra a mudanca de status no historico do pedido
			/* nao ha necessidade disso pois o comando setState, acima, ja grava um registro no historico do pedido
			$order->addStatusToHistory($order->getStatus(), $msg, $notify);
			*/

			//envia e-mail
            $invoice->setEmailSent(true);
			$order->sendOrderUpdateEmail($notify, '');
            $invoice->sendEmail(true, '');

			$order->save();
        }
        else { //if(!$order->canInvoice())
            $notify = false;
            $msg = 'Não foi possível gerar a fatura automaticamente.';

            $order->addStatusToHistory($order->getStatus(), $msg, $notify);
            $order->save();
        }
	}
}