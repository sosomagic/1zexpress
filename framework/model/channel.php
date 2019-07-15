<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-5-24
 * Time: 下午12:03
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class channel_model_base extends dsy_model
{
    function __construct()
    {
        parent::model();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this);
    }

    function get_list($pri='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."channel ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql,$pri);
    }
    function get_track_list($pri='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."track ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql,$pri);
    }

    function get_channel($ids)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."channel where id in(".$ids.") ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql);
    }
    function get_channelList($pri='')  //会员中心渠道列表
    {
        $sql = "SELECT * FROM ".$this->db->prefix."channel where status=1 ORDER BY taxis ASC,id DESC ";
        return $this->db->get_all($sql,$pri);
    }
    function get_one($id,$field_id='id')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."channel WHERE ".$field_id."='".$id."'";
        return $this->db->get_one($sql);
    }
    function get_track($id,$field_id='id')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."track WHERE ".$field_id."='".$id."'";
        return $this->db->get_one($sql);
    }

    function weight(){
        return array('lb'=>'磅','kg'=>'千克');
    }
    //存储信息
    function save($data,$id="")
    {
        if(!$data || !is_array($data)) return false;
        if($id)
        {
            return $this->db->update_array($data,"channel",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"channel","replace");
        }
    }
    function save_track($data,$id="")
    {
        if(!$data || !is_array($data)) return false;
        if($id)
        {
            return $this->db->update_array($data,"track",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"track","replace");
        }
    }

    function update_status($id,$status=0)
    {
        $sql = "UPDATE ".$this->db->prefix."channel SET status='".$status."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    function update_sort($id,$taxis=255)
    {
        $sql = "UPDATE ".$this->db->prefix."channel SET taxis='".$taxis."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    /*function update_track_sort($id,$taxis=255)
    {
        $sql = "UPDATE ".$this->db->prefix."track SET taxis='".$taxis."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }*/
    //删除操作
    function del($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."channel WHERE id='".$id."'";
        $this->db->query($sql);
        //删除渠道对应的资费
        $sql = "DELETE FROM ".$this->db->prefix."channel_price WHERE channel_id='".$id."'";
        $this->db->query($sql);
        return true;
    }
    function track_del($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."track WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    function track_list($condition='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."track";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $sql .= " ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql);
    }
    function save_price($data,$id="")
    {
        if(!$data || !is_array($data)) return false;
        if($id)
        {
            return $this->db->update_array($data,"channel_price",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"channel_price","replace");
        }
    }
    function get_price($condition='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."channel_price where status=1";
        if($condition){
            $sql .= " and ".$condition;
        }
        $sql .= " ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql);
    }
    function get_oneprice($id,$field_id='id')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."channel_price WHERE ".$field_id."='".$id."'";
        return $this->db->get_one($sql);
    }
    function price_del($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."channel_price WHERE group_id='".$id."'";
        $this->db->query($sql);
        return true;
    }
    //会员组资费
    public function channel_list($gid)
    {
        $sql = "SELECT c.*,p.remove,p.rule,p.num,p.start_heavy,p.first_price,p.second_price FROM ".$this->db->prefix."channel c";
        $sql.= " LEFT JOIN ".$this->db->prefix."channel_price p ON(c.id=p.channel_id)";
        $sql.= " WHERE c.status=1 and group_id=".$gid." and c.status=1";
        $sql.= " ORDER BY c.taxis ASC";
        return $this->db->get_all($sql);
    }
    /*function get_onechannel($id,$field_id='id')
    {
        $sql = "SELECT c.*,p.remove,p.rule,p.num,p.start_price,p.start_heavy,p.first,p.first_price,p.second_heavy,p.second,p.second_price,p.third_heavy,p.third,p.third_price FROM ".$this->db->prefix."channel c";
        $sql.= " LEFT JOIN ".$this->db->prefix."channel_price p ON(c.id=p.channel_id)";
        $sql.= " WHERE ".$field_id."='".$id."'";
        return $this->db->get_one($sql);
    }*/
    public function modify_price($data,$cid)
    {
        //$sql = "UPDATE ".$this->db->prefix."order SET status='".$status."' WHERE id='".$id."'";
        //return $this->db->query($sql);
        return $this->db->update_array($data,"channel_price",array("channel_id"=>$cid));
    }
    //手机端获取channel
    function CounterChannel($pri='')  //会员中心渠道列表
    {
        $sql = "SELECT id,title as value FROM ".$this->db->prefix."channel where status=1 ORDER BY taxis ASC,id DESC ";
        return $this->db->get_all($sql,$pri);
    }
	function get_onechannelprice($condition='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."channel_price where status=1";
        if($condition){
            $sql .= " and ".$condition;
        }
        return $this->db->get_one($sql);
    }
}
