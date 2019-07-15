<?php
/**
 * 仅限WEB接口调用的内容块
 */
if(!defined("DSAIYIN_SET")){
	exit("<h1>Access Denied</h1>");
}
class list_model extends list_model_base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add_hits($id)
	{
		$sql = "UPDATE ".$this->db->prefix."list SET hits=hits+1 WHERE id='".$id."'";
		return $this->db->query($sql,false);
	}

	public function get_hits($id)
	{
		$sql = "SELECT hits FROM ".$this->db->prefix."list WHERE id='".$id."'";
		$rs = $this->db->get_one($sql);
		return $rs['hits'];
	}
    //前台会员中心公告列表
    function notice_list($condition="",$offset=0,$psize=5)
    {
        $sql = "SELECT id,title,dateline FROM ".$this->db->prefix."list";
        if($condition){
            $sql.= " WHERE ".$condition;
        }
        $sql .= " ORDER BY dateline DESC,id DESC ";
        //if($psize && $psize>0){
            //$offset = intval($offset);
            $sql.= " LIMIT ".$offset.",".$psize;
        //}
        return $this->db->get_all($sql);
    }
    //会员中心弹出公告
    function get_notice()
    {
        $sql = "SELECT a.id, a.title,a.dateline,b.content FROM ".$this->db->prefix."list a ";
        $sql.= "LEFT JOIN ".$this->db->prefix."list_22 b ON(a.id=b.id) ";
        $sql.= "where a.cate_id=8 AND a.status=1 and a.attr='j' ";
        $sql .= "ORDER BY a.dateline DESC,a.id DESC LIMIT 0,1";
        return $this->db->get_one($sql);
    }
    //首页列表
    function get_arclist($cid,$offset=0,$psize=4)
    {
        $sql = "SELECT a.id,a.title,a.dateline,b.thumb,b.content FROM ".$this->db->prefix."list a ";
        $sql.= "LEFT JOIN ".$this->db->prefix."list_22 b ON(a.id=b.id) ";
        $sql.= "where a.cate_id=".$cid." AND a.status=1 ";
        $sql .= "ORDER BY a.dateline DESC,a.id DESC";
        $sql.= " LIMIT ".$offset.",".$psize;
        return $this->db->get_all($sql);
    }
}

?>