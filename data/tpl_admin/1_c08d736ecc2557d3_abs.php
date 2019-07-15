<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><table width="358" border="1" cellspacing="0" cellpadding="0" style="margin: 0 auto;font-size:12px;font-weight:bold; font-family:'微软雅黑'">
    <tr>
        <td height="45"><img src="css/images/plogo.jpg" width="100"/></td>
        <td colspan="2" align="center"><img src="<?php echo $sn_barcode;?>" height="45"/></td>
    </tr>
    <tr>
        <td colspan="3" height="120">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;font-size:12px;font-weight:bold; font-family:'微软雅黑'">
                <tr>
                    <td height="22">寄件人/From:</td>
                    <td><?php echo $rs['consignor'];?></td>
                    <td>电话/Tel:</td>
                    <td><?php echo $rs['consignor_tel'];?></td>
                </tr>
                <tr>
                    <td height="22">地址/Address:</td>
                    <td colspan="3"><?php echo $rs['consignor_address'];?></td>
                </tr>
                <tr>
                    <td height="22">收件人/To:</td>
                    <td><?php echo $address['fullname'];?></td>
                    <td>电话/Tel:</td>
                    <td><?php echo $address['mobile'];?></td>
                </tr>
                <tr>
                    <td height="30">地址/Address:</td>
                    <td colspan="3"><?php echo $address['province'];?><?php if($address['province'] != $address['city']){ ?><?php echo $address['city'];?><?php } ?><?php echo $address['county'];?><?php echo $address['address'];?></td>
                </tr>
                <tr>
                    <td>大客户代码:</td>
                    <td>95415</td>
                    <td>邮编/Post Code:</td>
                    <td><?php echo $address['zipcode'];?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="padding: 0px;">
            <table border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;font-size:12px;font-weight:bold; font-family:'微软雅黑'">
                <tr>
                    <td colspan="3" rowspan="3" valign="top" height="80" width="200" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">内件描述/Descriptions:<br />
                        <?php $list_id["num"] = 0;$product=is_array($product) ? $product : array();$list_id["total"] = count($product);$list_id["index"] = -1;foreach($product AS $key=>$value){ $list_id["num"]++;$list_id["index"]++; ?>
                        <?php echo $value['title'];?>*<?php echo $value['qty'];?><?php if($list_id['num'] != $list_id['total']){ ?>、<?php } ?>
                        <?php } ?>
                    </td>
                    <td colspan="3" width="148" style="border-bottom: 1px solid #000000;">实际重量:<?php echo $rs['jingzhong'];?>kg</td>
                </tr>
                <tr>
                    <td colspan="3" style="border-bottom: 1px solid #000000;">体积重量:&nbsp;kg</td>
                </tr>
                <tr>
                    <td rowspan="2" style="border-right: 1px solid #000000;">长/L</td>
                    <td rowspan="2" style="border-right: 1px solid #000000;">宽/W</td>
                    <td rowspan="2">高/H</td>
                </tr>
                <tr>
                    <td height="22" colspan="3" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">备注:<?php echo $rs['note'];?></td>
                </tr>
                <tr>
                    <td style="font-size:10px;border-right: 1px solid #000000;" colspan="3" >申报价值/Value:CNY&nbsp;&nbsp;&nbsp;&nbsp;原产地/Origin:美国</td>
                    <td style="border-right: 1px solid #000000;border-top: 1px solid #000000;">cm</td>
                    <td style="border-right: 1px solid #000000;border-top: 1px solid #000000;">cm</td>
                    <td style="border-top: 1px solid #000000;">cm</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;&nbsp;收件人签名:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;签收时间:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;日&nbsp;&nbsp;&nbsp;&nbsp;时</td>
    </tr>
    <tr>
        <td colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;font-size:12px;font-weight:bold; font-family:'微软雅黑'">
                <tr>
                    <td height="22">寄件人/From:</td>
                    <td><?php echo $rs['consignor'];?></td>
                    <td>电话/Tel:</td>
                    <td><?php echo $rs['consignor_tel'];?></td>
                </tr>
                <tr>
                    <td height="22">地址/Address:</td>
                    <td colspan="3"><?php echo $rs['consignor_address'];?></td>
                </tr>
                <tr>
                    <td height="22">收件人/To:</td>
                    <td><?php echo $address['fullname'];?></td>
                    <td>电话/Tel:</td>
                    <td><?php echo $address['mobile'];?></td>
                </tr>
                <tr>
                    <td height="44">地址/Address:</td>
                    <td colspan="3"><?php echo $address['province'];?><?php if($address['province'] != $address['city']){ ?><?php echo $address['city'];?><?php } ?><?php echo $address['county'];?><?php echo $address['address'];?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;font-size:12px;font-weight:bold; font-family:'微软雅黑'">
                <tr>
                    <td align="center" colspan="2"><img src="<?php echo $sn_barcode;?>" height="45"/></td>
                </tr>
                <tr>
                    <td height="22">进口口岸:北京</td>
                    <td height="22">原寄地:美国</td>
                </tr>
                <tr>
                    <td height="22">客服电话:11183</td>
                    <td height="22">关联单号:<?php echo $rs['sn'];?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>