<?php
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class tcpdf_lib
{
	/**
	 * 页面打印方向，P表示纵向，L表示横向
	**/
	private $page_orientation = 'P';

	/**
	 * 页面单位，默认是 mm
	**/
	private $page_unit = 'mm';


	/**
	 * 页面格式化，默认使用A4纸
	**/
	private $page_format = 'A6';

	/**
	 * PDF制作人
	**/
	private $page_author = 'DSYEX';

	/**
	 * PDF页头
	**/
	private $page_title = '面单打印';

	/**
	 * 间距设置
	**/
	private $margin_left = 2;
	private $margin_top = 2;
	private $margin_right = 2;
	private $margin_bottom = 2;
    private $psize = 1;
	public function __construct()
	{
		require_once(ROOT.'extension/tcpdf/config/tcpdf_config.php');
		require_once(ROOT.'extension/tcpdf/tcpdf.php');
	}

	public function orientation($val='')
	{
		if($val && ($val == 'P' || $val == 'L')){
			$this->page_orientation = $val;
		}
		return $this->page_orientation;
	}

	public function unit($val='')
	{
		if($val){
			$this->page_unit = $val;
		}
		return $this->page_unit;
	}

	public function page_format($val='')
	{
		if($val){
			$this->page_format = $val;
		}
		return $this->page_format;
	}

	public function author($val='')
	{
		if($val){
			$this->page_author = $val;
		}
		return $this->page_author;
	}

	public function title($val='')
	{
		if($val){
			$this->page_title = $val;
		}
		return $this->page_title;
	}

	/**
	 * 设置PDF间距
	 * @参数 $left 左间距
	 * @参数 $top 上间距
	 * @参数 $right 右间距
	 * @参数 $bottom 页脚间距
	 * @返回 true
	**/
	public function set_margin($left=5,$top=10,$right=5,$bottom=10)
	{
		$this->margin_left = $left;
		$this->margin_top = $top;
		$this->margin_right = $right;
		$this->margin_bottom = $bottom;
		return true;
	}
    public function psize($psize='')
    {
        if($psize){
            $this->psize = $psize;
        }
        return $this->psize;
    }
	public function create($html='',$file='',$download=false)
	{
		if(!$html){
			return false;
		}
		if(is_array($html)){
			return $this->create_array($html,$file,$download);
		}
		$PDF = new TCPDF($this->page_orientation,$this->page_unit, $this->page_format, true, 'UTF-8', false);
		$PDF->SetCreator('TCPDF');
		$PDF->SetAuthor($this->page_author);
		$PDF->SetTitle($this->page_title);

		// set default header data
		//$PDF->SetHeaderData($this->page_logo, $this->page_logo_width, $this->page_title, $this->page_author, array(0,64,255), array(0,64,128));
		//$PDF->setFooterData(array(0,64,0), array(0,64,128));

		// set header and footer fonts
		$PDF->setPrintHeader(false);
		$PDF->setPrintFooter(false);

		// set default monospaced font
		$PDF->SetDefaultMonospacedFont('courier');
		
		// set margins
		$PDF->SetMargins($this->margin_left, $this->margin_top, $this->margin_right);
		$PDF->setHeaderMargin(0);
		$PDF->setFooterMargin(0);

		// set auto page breaks
		$PDF->SetAutoPageBreak(true, $this->margin_bottom);

		// set image scale factor
		$PDF->setImageScale(1.25);
		$PDF->setFontSubsetting(true);
		$PDF->SetFont('cid0cs', '', 12,true);
		$PDF->AddPage();
		// set text shadow effect
		//$PDF->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
		$PDF->writeHTML($html, true, false, true, false, '');
		$type = $download ? 'D' :'F';
		$PDF->Output($file,$type);
	}
	public function create_array($list='',$file='',$download=false)
	{
		$PDF = new TCPDF($this->page_orientation,$this->page_unit, $this->page_format, true, 'UTF-8', false);
		$PDF->SetCreator('TCPDF');
		$PDF->SetAuthor($this->page_author);
		$PDF->SetTitle($this->page_title);

		// set default header data
		//$PDF->SetHeaderData($this->page_logo, $this->page_logo_width, $this->page_title, $this->page_author, array(0,64,255), array(0,64,128));
		//$PDF->setFooterData(array(0,64,0), array(0,64,128));

		// set header and footer fonts
		$PDF->setPrintHeader(false);
		$PDF->setPrintFooter(false);

		// set default monospaced font
		$PDF->SetDefaultMonospacedFont('courier');
		
		// set margins
		$PDF->SetMargins($this->margin_left, $this->margin_top, $this->margin_right);
		$PDF->setHeaderMargin(0);
		$PDF->setFooterMargin(0);

		// set auto page breaks
		$PDF->SetAutoPageBreak(true, $this->margin_bottom);

		// set image scale factor
		$PDF->setImageScale(1.25);
		$PDF->setFontSubsetting(true);
		$PDF->SetFont('cid0cs', '', 12,true);
        if($this->psize>1){
            $list = array_chunk($list,$this->psize);
            foreach($list as $key=>$value){
                $html = '<table border="0" cellspacing="5" cellpadding="5">';
                foreach($value as $k=>$v){
                    if(($k+1)%2==1){
                        $html .="<tr><td>";
                    }
                    $html .= $v;
                    if(($k+1)%2==1){
                        $html .="</td><td>";
                    }
                    if(($k+1)%2==0){
                        $html .="</td></tr>";
                    }
                }
                $html .= '</table>';
                $PDF->AddPage();
                $PDF->writeHTML($html, true, false, true, false, '');
            }
        }else{
            foreach($list as $key=>$value){
                $PDF->AddPage();
                $PDF->writeHTML($value, true, false, true, false, '');
            }
        }
		$type = $download ? 'D' :'F';
		$PDF->Output($file,$type);
	}
}
