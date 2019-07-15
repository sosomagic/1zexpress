<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<link href="css/print.css" rel="stylesheet" type="text/css" />
<div id="box">
	<div class="top border-bot">
		<div class="logo fl"><img src="css/images/plogo.jpg" width="115" height="48" /></div>
		<div class="top_rig fr"><img src="<?php echo $barcode;?>"  /></div>
	</div>
	<div class="send ">
		<div class="send_n fl">
			<div class="send_a fl">寄件人/From:</div>
			<div class="send_b fr"><?php echo $rs['consignor'];?></div>
		</div>
		<div class="send_n fr">
			<div class="send_d fl">电话/Tel:</div>
			<div class="send_e fr"><?php echo $rs['consignor_tel'];?></div>
		</div>
	   
	</div>
	 <div class="send border-bot">
		<div class="send_f fl">地址/Address:</div>
		<div class="send_c fr"><?php echo $rs['consignor_address'];?></div>
	</div>
	<div class="send">
		<div class="send_n fl">
			<div class="send_a fl">收件人/To:</div>
			<div class="send_b fr"><?php echo $address['fullname'];?></div>
		</div>
		<div class="send_n fr">
			<div class="send_d fl">电话/Tel:</div>
			<div class="send_e fr"><?php echo $address['mobile'];?></div>
		</div>
	</div>
	<div class="send border-bot" style="height:40px;">
		<div class="send_f fl">地址/Address:</div>
		<div class="send_c fr"><?php echo $address['province'];?><?php if($address['province'] != $address['city']){ ?><?php echo $address['city'];?><?php } ?><?php echo $address['county'];?><?php echo $address['address'];?></div>
	</div>
	<div class="send border-bot">
		<div class="send_n fl">
			<div class="send_a fl">大客户代码:</div>
			<div class="send_b fr"></div>
		</div>
		<div class="send_n fr">
			<div class="send_g fl">邮编/Post Code:</div>
			<div class="send_d fr"><?php echo $address['zipcode'];?></div>
		</div>
	</div>
	<div class="msgL fl">
		<div class="msgL_na">内件描述/Descriptions:</div>
		<div class="msgL_nb">
			<?php $product_id["num"] = 0;$product=is_array($product) ? $product : array();$product_id["total"] = count($product);$product_id["index"] = -1;foreach($product AS $key=>$value){ $product_id["num"]++;$product_id["index"]++; ?>
			<?php echo $value['title'];?>*<?php echo $value['qty'];?>&nbsp;&nbsp;
			<?php } ?>
		</div>
	</div>
	<div class="msgR fr">
		<div class="msgR_n">
			<div class="msgR_a fl">实际重量:</div>
			<div class="msgR_b fr"><span><?php echo $rs['jingzhong'];?></span>LB</div>
		</div>
		<div class="msgR_n">
			<div class="msgR_a fl">体积重量:</div>
			<div class="msgR_b fr"><span>&nbsp;</span>LB</div>
		</div>
		<div class="msgR_z">
			<div class="msgR_zn">
				<span>长/L</span>
				<span>宽/W</span>
				<span>高/H</span>
			</div>
			<div class="msgR_zn">
				<span> cm</span>
				<span> cm</span>
				<span> cm</span>
			</div>
		</div>
	</div>
	<div class="send border-bot">
		<div class="send_n fl">
			<div class="send_g fl">申报价值/Value:</div>
			<div class="send_a fr"><?php echo $rs['val'];?> CNY</div>
		</div>
		<div class="send_n fr">
			<div class="send_g fl">原产地/Origin:</div>
			<div class="send_d fr">美国</div>
		</div>
	</div>
    <div class="send border-bot">
		<div class="send_n fl">
			<div class="send_a fl">收件人签名:</div>
			<div class="send_b fr"></div>
		</div>
		<div class="send_n fr">
			<div class="send_g fl">签收时间:</div>
			<div class="send_d fr"></div>
		</div>
	</div> 
    <div class="send ">
		<div class="send_n fl">
			<div class="send_a fl">寄件人/From:</div>
			<div class="send_b fr"><?php echo $rs['consignor'];?></div>
		</div>
		<div class="send_n fr">
			<div class="send_d fl">电话/Tel:</div>
			<div class="send_e fr"><?php echo $rs['consignor_tel'];?></div>
		</div>
	</div>
	<div class="send border-bot">
		<div class="send_f fl">地址/Address:</div>
		<div class="send_c fr"><?php echo $rs['consignor_address'];?></div>
	</div>
	<div class="send">
		<div class="send_n fl">
			<div class="send_a fl">收件人/To:</div>
			<div class="send_b fr"><?php echo $address['fullname'];?></div>
		</div>
		<div class="send_n fr">
			<div class="send_d fl">电话/Tel:</div>
			<div class="send_e fr"><?php echo $address['mobile'];?></div>
		</div>
	</div>
	<div class="send border-bot" style="height:40px;">
		<div class="send_f fl">地址/Address:</div>
		<div class="send_c fr"><?php echo $address['province'];?><?php if($address['province'] != $address['city']){ ?><?php echo $address['city'];?><?php } ?><?php echo $address['county'];?><?php echo $address['address'];?></div>
	</div>
	<div class="send text-center" style="height:50px;"><img src="<?php echo $barcode;?>"/></div>
    <div class="send border-bot">
		<div class="send_n fl">
			<div class="send_a fl">进口口岸:</div>
			<div class="send_b fr">北京</div>
		</div>
		<div class="send_n fr">
			<div class="send_d fl">原寄地:</div>
			<div class="send_e fr">美国</div>
		</div>
	</div> 
	<div class="send">
		<div class="send_n fl">
			<div class="send_a fl">客服电话:</div>
			<div class="send_b fr">11183</div>
		</div>
		<div class="send_n fr">
			<div class="send_d fl">关联单号：</div>
			<div class="send_e fr"><?php echo $rs['sn'];?></div>
		</div>
	</div>   	
</div>
<?php $this->output("foot_open","file"); ?>