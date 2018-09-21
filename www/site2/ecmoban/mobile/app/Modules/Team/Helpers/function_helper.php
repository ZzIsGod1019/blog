<?php


function team_categories()
{
    $sql = 'SELECT c.id,c.name,c.parent_id,c.sort_order,c.status FROM {pre}team_category as c ' .
        "WHERE c.parent_id = 0 AND c.status = 1 ORDER BY c.sort_order ASC, c.id ASC";

    $res = $GLOBALS['db']->getAll($sql);
    foreach ($res as $key => $row) {
        $cat_arr[$key]['id'] = $row['id'];
        $cat_arr[$key]['name'] = $row['name'];
    }
    return $cat_arr;
}


function team_get_child_tree($id = 0)
{
    $three_arr = [];
    $sql = "SELECT count(*) FROM {pre}team_category WHERE parent_id = '$id' AND status = 1 ";
    if ($GLOBALS['db']->getOne($sql) || $id == 0) {
        $child_sql = 'SELECT c.id,c.name,c.parent_id,c.sort_order,c.status,c.tc_img ' .
            'FROM {pre}team_category as c ' .
            " WHERE c.parent_id = '$id' AND c.status = 1 GROUP BY c.id ORDER BY c.sort_order ASC, c.id ASC";
        $res = $GLOBALS['db']->getAll($child_sql);
        foreach ($res as $row) {
            if ($row['status']) {
                $three_arr[$row['id']]['id'] = $row['id'];
                $three_arr[$row['id']]['name'] = $row['name'];
                $three_arr[$row['id']]['tc_img'] = get_image_path($row['tc_img']);
                $three_arr[$row['id']]['url'] = url('team/index/category', ['tc_id' => $row['id']]);
            }
            if (isset($row['cat_id']) != null) {
                $three_arr[$row['id']]['cat_id'] = $this->team_get_child_tree($row['id']);
            }
        }
    }
    return $three_arr;
}



function team_category_goods($tc_id = 0, $keywords = '', $size = 10, $page = 1, $intro = '', $sort, $order, $brand, $min, $max)
{
    
    $where .= " g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.review_status>2 and tg.is_team = 1 and tg.is_audit = 2 ";
    if ($tc_id > 0) {
        $where .= " AND  tg.tc_id = $tc_id ";
    }

    if ($intro) {
        switch ($intro) {
            case 'best':
                $where .= ' AND g.is_best = 1 ';
                break;
            case 'new':
                $where .= ' AND g.is_new = 1 ';
                break;
            case 'hot':
                $where .= ' AND g.is_hot = 1 ';
                break;
            case 'promotion':
                $time = gmtime();
                $where .= " AND g.promote_price > 0 AND g.promote_start_date <= '$time' AND g.promote_end_date >= '$time' ";
                break;
            default:
                $where .= '';
        }
    }

    $leftJoin = '';
    if ($brand > 0) {
        $leftJoin .= "LEFT JOIN " . $GLOBALS['ecs']->table('brand') . " AS b " . "ON b.brand_id = g.brand_id ";
        $where .= "AND g.brand_id = '$brand' ";
    }

    if (!empty($keywords)) {
        $where .= " AND (g.goods_name LIKE '%$keywords%' OR g.goods_sn LIKE '%$keywords%' OR g.keywords LIKE '%$keywords%')";
    }
    

    if ($min > 0) {
        $where .= " AND  tg.team_price >= $min ";
    }

    if ($max > 0) {
        $where .= " AND tg.team_price <= $max ";
    }

    if ($sort == 'last_update') {
        $sort = 'g.last_update';
    }

    $arr = [];
    $sql = 'SELECT g.goods_id, g.goods_name, g.shop_price,g.market_price,g.goods_number, g.goods_name_style, g.comments_number, g.sales_volume,g.goods_thumb , g.goods_img,g.model_price, tg.team_price, tg.team_num,tg.limit_num ' .
        ' FROM {pre}team_goods AS tg ' .
        'LEFT JOIN {pre}goods AS g ON tg.goods_id = g.goods_id ' . $leftJoin . " WHERE $where ORDER BY $sort $order";
    
    $goods_list = $GLOBALS['db']->getAll($sql);
    $total = is_array($goods_list) ? count($goods_list) : 0;
    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);
    foreach ($res as $key => $val) {
        $arr[$key]['goods_name'] = $val['goods_name'];
        $arr[$key]['shop_price'] = price_format($val['shop_price']);
        $arr[$key]['market_price'] = price_format($val['market_price']);
        $arr[$key]['goods_number'] = $val['goods_number'];
        $arr[$key]['sales_volume'] = $val['sales_volume'];

        $arr[$key]['goods_img'] = get_image_path($val['goods_img']);
        $arr[$key]['goods_thumb'] = get_image_path($val['goods_thumb']);
        $arr[$key]['url'] = url('team/goods/index', ['id' => $val['goods_id']]);
        $arr[$key]['team_price'] = price_format($val['team_price']);
        $arr[$key]['team_num'] = $val['team_num'];
        $arr[$key]['limit_num'] = $val['limit_num'];
    }

    return ['list' => array_values($arr), 'totalpage' => ceil($total / $size)];
}



function team_goods($size = 10, $page = 1, $type = 'limit_num')
{
    $where .= " g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.review_status>2 and tg.is_team = 1 and tg.is_audit = 2 ";
    switch ($type) {
        case 'limit_num':
            $type = '  ORDER BY tg.limit_num DESC';
            break;
        case 'is_new':
            $type = 'AND g.is_new = 1 ORDER BY g.add_time DESC';
            break;
        case 'is_hot':
            $type = 'AND g.is_hot = 1';
            break;
        case 'is_best':
            $type = 'AND g.is_best = 1';
            break;
        default:
            $type = '1';
    }
    $arr = [];
    $sql = 'SELECT g.goods_id, g.goods_name, g.shop_price, g.goods_name_style, g.comments_number, g.sales_volume, g.market_price, g.goods_thumb , g.goods_img, tg.team_price, tg.team_num,tg.limit_num' .
        ' FROM {pre}team_goods AS tg ' .
        'LEFT JOIN {pre}goods AS g ON tg.goods_id = g.goods_id ' .
        "WHERE $where $type";
    $goods_list = $GLOBALS['db']->getAll($sql);
    $total = is_array($goods_list) ? count($goods_list) : 0;
    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);
    foreach ($res as $key => $val) {
        if ($key < 3 && $page < 2) {
            $arr[$key]['key'] = $key + 1;
        }
        $arr[$key]['goods_name'] = $val['goods_name'];
        $arr[$key]['shop_price'] = price_format($val['shop_price']);
        $arr[$key]['goods_img'] = get_image_path($val['goods_img']);
        $arr[$key]['goods_thumb'] = get_image_path($val['goods_thumb']);
        $arr[$key]['url'] = url('team/goods/index', ['id' => $val['goods_id']]);
        $arr[$key]['team_price'] = price_format($val['team_price']);
        $arr[$key]['team_num'] = $val['team_num'];
        $arr[$key]['limit_num'] = $val['limit_num'];
    }
    return ['list' => array_values($arr), 'totalpage' => ceil($total / $size)];
}


function team_new_goods($type, $ru_id = 0)
{
    $where .= " g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.review_status>2 and tg.is_team = 1 and tg.is_audit = 2 ";
    if ($type == 'is_new') {
        $where .= " and g.is_new =$type and g.user_id =$ru_id  ";
    }
    $sql = 'SELECT g.goods_id, g.goods_name, g.shop_price, g.goods_name_style, g.comments_number, g.sales_volume, g.market_price, g.goods_thumb , g.goods_img, tg.team_price, tg.team_num,tg.limit_num' .
        ' FROM {pre}team_goods AS tg ' .
        'LEFT JOIN {pre}goods AS g ON tg.goods_id = g.goods_id ' .
        "WHERE $where limit 0,10";
    $goods_list = $GLOBALS['db']->getAll($sql);

    foreach ($goods_list as $key => $val) {
        $arr[$key]['goods_name'] = $val['goods_name'];
        $arr[$key]['shop_price'] = price_format($val['shop_price']);
        $arr[$key]['goods_img'] = get_image_path($val['goods_img']);
        $arr[$key]['goods_thumb'] = get_image_path($val['goods_thumb']);
        $arr[$key]['url'] = url('team/goods/index', ['id' => $val['goods_id']]);
        $arr[$key]['team_price'] = price_format($val['team_price']);
        $arr[$key]['team_num'] = $val['team_num'];
        $arr[$key]['limit_num'] = $val['limit_num'];
    }
    return $arr;
}


function get_good_comment($id, $rank = null, $hasgoods = 0, $start = 0, $size = 10)
{
    if (empty($id)) {
        return false;
    }
    $where = '';

    $rank = (empty($rank) && $rank !== 0) ? '' : intval($rank);

    if ($rank == 4) {
        
        $where = ' AND  comment_rank in (4, 5)';
    } elseif ($rank == 2) {
        
        $where = ' AND  comment_rank in (2, 3)';
    } elseif ($rank === 0) {
        
        $where = ' AND  comment_rank in (0, 1)';
    } elseif ($rank == 1) {
        
        $where = ' AND  comment_rank in (0, 1)';
    } elseif ($rank == 5) {
        $where = ' AND  comment_rank in (0, 1, 2, 3, 4,5)';
    }

    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('comment') . " WHERE id_value = '" . $id . "' and comment_type = 0 and status = 1 and parent_id = 0 " . $where . " ORDER BY comment_id DESC LIMIT $start, $size";

    $comment = $GLOBALS['db']->getAll($sql);
    $arr = [];
    if ($comment) {
        $ids = '';
        foreach ($comment as $key => $row) {
            $ids .= $ids ? ",$row[comment_id]" : $row['comment_id'];
            $arr[$row['comment_id']]['id'] = $row['comment_id'];
            $arr[$row['comment_id']]['email'] = $row['email'];
            $arr[$row['comment_id']]['content'] = str_replace('\r\n', '<br />', $row['content']);
            $arr[$row['comment_id']]['content'] = nl2br(str_replace('\n', '<br />', $arr[$row['comment_id']]['content']));
            $arr[$row['comment_id']]['rank'] = $row['comment_rank'];
            $arr[$row['comment_id']]['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);

            
            $user_nick = get_user_default($row['user_id']);
            $arr[$row['comment_id']]['username'] = encrypt_username($user_nick['nick_name']);
            
            $arr[$row['comment_id']]['headerimg'] = $user_nick['user_picture'];

            if ($row['order_id'] && $hasgoods) {
                $sql = "SELECT o.goods_id, o.goods_name, o.goods_attr, g.goods_img FROM " . $GLOBALS['ecs']->table('order_goods') . " o LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " g ON o.goods_id = g.goods_id WHERE o.order_id = '" . $row['order_id'] . "' ORDER BY rec_id DESC";
                $goods = $GLOBALS['db']->getAll($sql);
                if ($goods) {
                    foreach ($goods as $k => $v) {
                        $goods[$k]['goods_img'] = get_image_path($v['goods_img']);
                        $goods[$k]['goods_attr'] = str_replace('\r\n', '<br />', $v['goods_attr']);
                    }
                }
                $arr[$row['comment_id']]['goods'] = $goods;
            }
            $sql = "SELECT img_thumb FROM {pre}comment_img WHERE comment_id = " . $row['comment_id'];
            $comment_thumb = $GLOBALS['db']->getCol($sql);
            if (count($comment_thumb) > 0) {
                foreach ($comment_thumb as $k => $v) {
                    $comment_thumb[$k] = get_image_path($v);
                }
                $arr[$row['comment_id']]['thumb'] = $comment_thumb;
            } else {
                $arr[$row['comment_id']]['thumb'] = 0;
            }
        }

        
        if ($ids) {
            $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('comment') . " WHERE parent_id IN( $ids )";
            $res = $GLOBALS['db']->query($sql);
            foreach ($res as $row) {
                $arr[$row['parent_id']]['re_content'] = nl2br(str_replace('\n', '<br />', htmlspecialchars($row['content'])));
                $arr[$row['parent_id']]['re_add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
                $arr[$row['parent_id']]['re_email'] = $row['email'];
                $arr[$row['parent_id']]['re_username'] = $row['user_name'];
            }
        }
        $arr = array_values($arr);
    }
    return $arr;
}



function get_good_comment_as($id, $rank = null, $hasgoods = 0, $start = 0, $size = 10)
{
    if (empty($id)) {
        return false;
    }
    $where = '';

    $rank = (empty($rank) && $rank !== 0) ? '' : intval($rank);

    if ($rank == 4) {
        
        $where = ' AND  comment_rank in (4, 5)';
    } elseif ($rank == 2) {
        
        $where = ' AND  comment_rank in (2, 3)';
    } elseif ($rank === 0) {
        
        $where = ' AND  comment_rank in (0, 1)';
    } elseif ($rank == 1) {
        
        $where = ' AND  comment_rank in (0, 1)';
    } elseif ($rank == 5) {
        $where = ' AND  comment_rank in (0, 1, 2, 3, 4,5)';
    }

    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('comment') . " WHERE id_value = '" . $id . "' and comment_type = 0 and status = 1 and parent_id = 0 " . $where . " ORDER BY comment_id DESC LIMIT $start, $size";

    $comment = $GLOBALS['db']->getAll($sql);


    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('comment') . " WHERE id_value = '" . $id . "' and comment_type = 0 and status = 1 and parent_id = 0 " . $where;

    $max = $GLOBALS['db']->getAll($sql);

    $max = ceil(count($max) / $size);
    $arr = [];
    if ($comment) {
        $ids = '';
        foreach ($comment as $key => $row) {
            $ids .= $ids ? ",$row[comment_id]" : $row['comment_id'];
            $arr[$row['comment_id']]['id'] = $row['comment_id'];
            $arr[$row['comment_id']]['email'] = $row['email'];

            $users = get_user_default($row['user_id']);
            $arr[$row['comment_id']]['username'] = encrypt_username($users['nick_name']);
            $arr[$row['comment_id']]['user_picture'] = get_image_path($users['user_picture']);

            $arr[$row['comment_id']]['content'] = str_replace('\r\n', '<br />', $row['content']);
            $arr[$row['comment_id']]['content'] = nl2br(str_replace('\n', '<br />', $arr[$row['comment_id']]['content']));
            $arr[$row['comment_id']]['rank'] = $row['comment_rank'];
            $arr[$row['comment_id']]['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
            if ($row['order_id'] && $hasgoods) {
                $sql = "SELECT o.goods_id, o.goods_name, o.goods_attr, g.goods_img FROM " . $GLOBALS['ecs']->table('order_goods') . " o LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " g ON o.goods_id = g.goods_id WHERE o.order_id = '" . $row['order_id'] . "' ORDER BY rec_id DESC";
                $goods = $GLOBALS['db']->getAll($sql);
                if ($goods) {
                    foreach ($goods as $k => $v) {
                        $goods[$k]['goods_img'] = get_image_path($v['goods_img']);
                        $goods[$k]['goods_attr'] = str_replace('\r\n', '<br />', $v['goods_attr']);
                    }
                }
                $arr[$row['comment_id']]['goods'] = $goods;
            }
            $sql = "SELECT img_thumb FROM {pre}comment_img WHERE comment_id = " . $row['comment_id'];
            $comment_thumb = $GLOBALS['db']->getCol($sql);
            if (count($comment_thumb) > 0) {
                foreach ($comment_thumb as $k => $v) {
                    $comment_thumb[$k] = get_image_path($v);
                }
                $arr[$row['comment_id']]['thumb'] = $comment_thumb;
            } else {
                $arr[$row['comment_id']]['thumb'] = 0;
            }
        }

        
        if ($ids) {
            $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('comment') . " WHERE parent_id IN( $ids )";
            $res = $GLOBALS['db']->query($sql);
            foreach ($res as $row) {
                $arr[$row['parent_id']]['re_content'] = nl2br(str_replace('\n', '<br />', htmlspecialchars($row['content'])));
                $arr[$row['parent_id']]['re_add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
                $arr[$row['parent_id']]['re_email'] = $row['email'];
                $arr[$row['parent_id']]['re_username'] = $row['user_name'];
            }
        }
        $arr = array_values($arr);
    }
    return ['arr' => $arr, 'max' => $max];
}



function commentCol($id)
{
    if (empty($id)) {
        return false;
    }
    $sql = "SELECT count(comment_id) as num FROM {pre}comment WHERE id_value =" . $id . ' and comment_type = 0 and status = 1 and parent_id = 0';
    $arr['all_comment'] = $GLOBALS['db']->getOne($sql);
    $sql = "SELECT count(comment_id) as num FROM {pre}comment WHERE id_value =" . $id . ' AND  comment_rank in (4, 5) and comment_type = 0 and status = 1 and parent_id = 0 ';
    $arr['good_comment'] = $GLOBALS['db']->getOne($sql);
    $sql = "SELECT count(comment_id) as num FROM {pre}comment WHERE id_value =" . $id . ' AND  comment_rank in (2, 3) and comment_type = 0 and status = 1 and parent_id = 0 ';
    $arr['in_comment'] = $GLOBALS['db']->getOne($sql);
    $sql = "SELECT count(comment_id) as num FROM {pre}comment WHERE id_value =" . $id . ' AND  comment_rank in (0, 1) and comment_type = 0 and status = 1 and parent_id = 0 ';
    $arr['rotten_comment'] = $GLOBALS['db']->getOne($sql);
    $sql = "SELECT count( DISTINCT b.comment_id) as num FROM {pre}comment as a LEFT JOIN {pre}comment_img as b ON a.id_value=b.goods_id WHERE a.id_value =" . $id . " and a.comment_type = 0 and a.status = 1 and a.parent_id = 0 and b.img_thumb != ''";
    $arr['img_comment'] = $GLOBALS['db']->getOne($sql);
    foreach ($arr as $key => $val) {
        $arr[$key] = empty($val) ? 0 : $arr[$key];
    }
    return $arr;
}


function get_goods_attr_ajax($goods_id, $goods_attr, $goods_attr_id)
{
    $arr = [];
    $arr['attr_id'] = '';
    $goods_attr = implode(",", $goods_attr);
    if ($goods_attr) {
        if ($goods_attr_id) {
            $goods_attr_id = implode(",", $goods_attr_id);
            $where = " AND ga.goods_attr_id IN($goods_attr_id)";
        } else {
            $where = '';
        }

        $sql = "SELECT ga.goods_attr_id, ga.attr_id, ga.attr_value  FROM " . $GLOBALS['ecs']->table('goods_attr') . " AS ga" .
            " LEFT JOIN " . $GLOBALS['ecs']->table('attribute') . " AS a ON ga.attr_id = a.attr_id " .
            " WHERE ga.attr_id IN($goods_attr) AND ga.goods_id = '$goods_id' $where AND a.attr_type > 0 ORDER BY a.sort_order, ga.attr_id";
        $res = $GLOBALS['db']->getAll($sql);

        foreach ($res as $key => $row) {
            $arr[$row['attr_id']][$row['goods_attr_id']] = $row;

            $arr['attr_id'] .= $row['attr_id'] . ",";
        }

        if ($arr['attr_id']) {
            $arr['attr_id'] = substr($arr['attr_id'], 0, -1);
            $arr['attr_id'] = explode(",", $arr['attr_id']);
        } else {
            $arr['attr_id'] = [];
        }
    }

    return $arr;
}


function tean_get_final_price($goods_id, $goods_num = '1', $is_spec_price = false, $spec = [], $warehouse_id = 0, $area_id = 0, $type = 0, $presale = 0, $add_tocart = 1, $show_goods = 0)
{
    $spec_price = 0;
    $warehouse_area['warehouse_id'] = $warehouse_id;
    $warehouse_area['area_id'] = $area_id;
    if ($is_spec_price) {
        if (!empty($spec)) {
            $spec_price = spec_price($spec, $goods_id, $warehouse_area);
        }
    }
    $final_price = '0'; 
    
    
    
    $sql = "SELECT mp.user_price, mp.user_price, " .
        " g.promote_start_date, g.promote_end_date" .
        " FROM " . $GLOBALS['ecs']->table('goods') . " AS g " .
        " LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ON mp.goods_id = g.goods_id  " .
        " WHERE g.goods_id = '" . $goods_id . "'" .
        " AND g.is_delete = 0 LIMIT 1";

    $goods = $GLOBALS['db']->getRow($sql);
    
    $team = dao('team_goods')->field('team_price,team_num,astrict_num')->where(['goods_id' => $goods_id,'is_team' => '1'])->find();
    if ($GLOBALS['_CFG']['add_shop_price'] == 1) {
        $final_price = $team['team_price'];
        $final_price += $spec_price;
    } else {
        $final_price = $team['team_price'];
    }
    
    return $final_price;
}


function team_goods_info($goods = 0, $t_id = 0)
{
    $sql = 'SELECT g.*, tg.team_price, tg.team_num,tg.astrict_num FROM ' . $GLOBALS['ecs']->table('team_goods') . 'AS tg LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON tg.goods_id = g.goods_id ' . "WHERE tg.goods_id = $goods and tg.id = $t_id  and is_team = 1";
    $goods = $GLOBALS['db']->getRow($sql);
    return $goods;
}

function team_is_failure($team_id = 0)
{
    $sql = 'SELECT tg.id,tg.team_price,tg.team_num,tg.astrict_num,tg.is_team FROM ' . $GLOBALS['ecs']->table('team_log') . 'AS tl LEFT JOIN ' . $GLOBALS['ecs']->table('team_goods') . ' AS tg ON tl.t_id = tg.id ' . "WHERE  tl.team_id = $team_id";
    $team = $GLOBALS['db']->getRow($sql);
    return $team;
}




function team_goods_log($goods_id = 0)
{
    $sql = "select tl.team_id, tl.start_time,o.team_parent_id,g.goods_id,tg.validity_time ,tg.team_num from " . $GLOBALS['ecs']->table('team_log') . " as tl LEFT JOIN " . $GLOBALS['ecs']->table('order_info') . " as o ON tl.team_id = o.team_id LEFT JOIN  " . $GLOBALS['ecs']->table('goods') . " as g ON tl.goods_id = g.goods_id LEFT JOIN " . $GLOBALS['ecs']->table('team_goods') . " AS tg ON tl.t_id = tg.id  " . " where tl.goods_id = $goods_id and tl.status <1 and tl.is_show = 1 and o.extension_code ='team_buy' and o.team_parent_id > 0 and pay_status = 2 and tg.is_team = 1";
    $result = $GLOBALS['db']->getAll($sql);
    foreach ($result as $key => $vo) {
        $validity_time = $vo['start_time'] + ($vo['validity_time'] * 3600);
        $goods[$key]['team_id'] = $vo['team_id'];
        $goods[$key]['user_id'] = $vo['team_parent_id'];
        $goods[$key]['end_time'] = local_date($GLOBALS['_CFG']['time_format'], $vo['start_time']+($vo['validity_time']*3600));
        $goods[$key]['end_time'] = local_date('Y/m/d H:i:s', $vo['start_time'] + ($vo['validity_time'] * 3600));
        $goods[$key]['surplus'] = $vo['team_num'] - surplus_num($vo['team_id']);

        
        $user_nick = get_user_default($vo['team_parent_id']);
        $goods[$key]['user_name'] = encrypt_username($user_nick['nick_name']);
        $goods[$key]['headerimg'] = $user_nick['user_picture'];

        
        if ($validity_time <= gmtime()) {
            unset($goods[$key]);
        }
    }
    return $goods;
}


function surplus_num($team_id = 0, $failure = 0)
{
    if ($failure == 2) {
        $sql = "SELECT count(order_id) as num  FROM " . $GLOBALS['ecs']->table('order_info') . " WHERE team_id = '" . $team_id . "' AND extension_code = 'team_buy' ";
    } else {
        $sql = "SELECT count(order_id) as num  FROM " . $GLOBALS['ecs']->table('order_info') . " WHERE team_id = '" . $team_id . "' AND extension_code = 'team_buy' and (pay_status = '" . PS_PAYED . "' or order_status = 4) ";
    }
    $res = $GLOBALS['db']->getRow($sql);
    return $res['num'];
}


function my_team_goods($user_id, $type = 1, $page = 1, $size = 10)
{

    
    switch ($type) {
        case '1':
            $where = "";
            break;
        case '2':
            $where = " and t.status < 1 and '" . gmtime() . "'< (t.start_time+(tg.validity_time*3600)) and o.order_status != 2 and tg.is_team = 1 ";
            break;
        case '3':
            $where = " and t.status = 1 ";
            break;
        case '4':
            $where = " and t.status < 1 and ('" . gmtime() . "' > (t.start_time+(tg.validity_time*3600)) || tg.is_team != 1)";
            break;

        default:
            $where = '';
    }
    $sql = "select o.order_id,o.order_status,o.pay_status,t.goods_id,t.team_id,t.start_time,t.status,g.goods_name,g.goods_thumb,g.goods_img,tg.validity_time,tg.team_num,tg.team_price,tg.limit_num from " . $GLOBALS['ecs']->table('order_info') . " as o left join " . $GLOBALS['ecs']->table('team_log') . " as t on o.team_id = t.team_id left join " . $GLOBALS['ecs']->table('team_goods') . " as tg on t.t_id = tg.id left join " . $GLOBALS['ecs']->table('goods') . " as g on g.goods_id = tg.goods_id" . " where o.user_id =$user_id and o.extension_code ='team_buy'  and t.is_show = 1 $where  ORDER BY o.add_time DESC ";
    $goods_list = $GLOBALS['db']->getAll($sql);
    $total = is_array($goods_list) ? count($goods_list) : 0;
    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);

    foreach ($res as $key => $vo) {
        $goods[$key]['id'] = $vo['goods_id'];
        $goods[$key]['team_id'] = $vo['team_id'];
        $goods[$key]['order_id'] = $vo['order_id'];
        $goods[$key]['pay_status'] = $vo['pay_status'];
        $goods[$key]['goods_name'] = $vo['goods_name'];
        $goods[$key]['goods_img'] = get_image_path($vo['goods_img']);
        $goods[$key]['goods_thumb'] = get_image_path($vo['goods_thumb']);
        $goods[$key]['team_num'] = $vo['team_num'];
        if ($type == 4) {
            $goods[$key]['limit_num'] = surplus_num($vo['team_id'], 2);
        } else {
            $goods[$key]['limit_num'] = surplus_num($vo['team_id']);
        }
        
        $goods[$key]['team_price'] = $vo['team_price'];
        $goods[$key]['url'] = url('goods/index', ['id' => $vo['goods_id']]);
        $goods[$key]['order_url'] = url('user/order/detail', ['order_id' => $vo['order_id']]);
        $goods[$key]['team_url'] = url('team/goods/teamwait', ['team_id' => $vo['team_id'], 'user_id' => $_SESSION['user_id']]);
        if ($vo['status'] == 1) {
            $goods[$key]['status'] = 1;
        }
        $validity_time = $vo['start_time'] + ($vo['validity_time'] * 3600);
        if ($validity_time <= gmtime() && $vo['status'] != 1 || $vo['order_status'] == 2) {
            $goods[$key]['status'] = 2;
        }
    }
    return ['list' => array_values($goods), 'totalpage' => ceil($total / $size)];
}



function get_cart_value($flow_type = 0)
{
    $where = '';
    if (!empty($_SESSION['user_id'])) {
        $c_sess = " c.user_id = '" . $_SESSION['user_id'] . "' ";
    } else {
        $c_sess = " c.session_id = '" . real_cart_mac_ip() . "' ";
    }

    $sql = "SELECT c.rec_id FROM " . $GLOBALS['ecs']->table('cart') .
        " AS c LEFT JOIN " . $GLOBALS['ecs']->table('goods') .
        " AS g ON c.goods_id = g.goods_id WHERE $where " . $c_sess .
        "AND rec_type = '$flow_type' order by c.rec_id asc";

    $goods_list = $GLOBALS['db']->getAll($sql);

    $rec_id = '';
    if ($goods_list) {
        foreach ($goods_list as $key => $row) {
            $rec_id .= $row['rec_id'] . ',';
        }

        $rec_id = substr($rec_id, 0, -1);
    }

    return $rec_id;
}


function cart_by_favourable($merchant_goods)
{
    foreach ($merchant_goods as $key => $row) { 
        $goods_num = 0;
        $package_goods_num = 0;
        $user_cart_goods = $row['goods_list'];
        
        $favourable_list = favourable_list($_SESSION['user_rank'], $row['ru_id']);
        
        $sort_favourable = sort_favourable($favourable_list);

        foreach ($user_cart_goods as $key1 => $row1) { 
            $row1['original_price'] = $row1['goods_price'] * $row1['goods_number'];

            if ($row1['extension_code'] == 'package_buy') {
                $package_goods_num++;
            } else {
                $goods_num++;
            }
            
            if (is_dir(APP_TEAM_PATH) == false) {
                if (isset($sort_favourable['by_all']) && $row1['extension_code'] != 'package_buy') {
                    foreach ($sort_favourable['by_all'] as $key2 => $row2) {
                        if ($row1['is_gift'] == 0) { 
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['act_id'] = $row2['act_id'];
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['act_name'] = $row2['act_name'];
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type'] = $row2['act_type'];
                            
                            switch ($row2['act_type']) {
                                case 0:
                                    $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满赠';
                                    $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = intval($row2['act_type_ext']);
                                    break;
                                case 1:
                                    $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满减';
                                    $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = number_format($row2['act_type_ext'], 2);
                                    break;
                                case 2:
                                    $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '折扣';
                                    $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = floatval($row2['act_type_ext'] / 10);
                                    break;

                                default:
                                    break;
                            }
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['min_amount'] = $row2['min_amount'];
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext'] = intval($row2['act_type_ext']);
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['cart_fav_amount'] = cart_favourable_amount($row2);
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['available'] = favourable_available($row2);
                            
                            $cart_favourable = cart_favourable();
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]);
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['favourable_used'] = favourable_used($row2, $cart_favourable);
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['left_gift_num'] = intval($row2['act_type_ext']) - (empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]));

                            

                            
                            if ($row2['gift']) {
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_gift_list'] = $row2['gift'];
                            }

                            
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['act_goods_list'][$row1['rec_id']] = $row1;
                        } else { 
                            $merchant_goods[$key]['new_list'][$row2['act_id']]['act_cart_gift'][$row1['rec_id']] = $row1;
                        }
                        break; 
                    }
                    continue;
                }

                

                if (isset($sort_favourable['by_category']) && $row1['extension_code'] != 'package_buy') {
                    
                    $get_act_range_ext = get_act_range_ext($_SESSION['user_rank'], $row['ru_id'], 1); 

                    $id_list = [];
                    foreach ($get_act_range_ext as $id) {
                        $id_list = array_merge($id_list, array_keys(cat_list(intval($id), 0)));
                    }
                    
                    $cat_id = $GLOBALS['db']->getOne("SELECT cat_id FROM " . $GLOBALS['ecs']->table('goods') . " WHERE goods_id = '$row1[goods_id]' ");
                    
                    $favourable_id_list = get_favourable_id($sort_favourable['by_category']);

                    
                    if ((in_array(trim($cat_id), $id_list) && $row1['is_gift'] == 0) || in_array($row1['is_gift'], $favourable_id_list)) {
                        foreach ($sort_favourable['by_category'] as $key2 => $row2) {
                            
                            $fav_act_range_ext = [];

                            
                            foreach (explode(',', $row2['act_range_ext']) as $id) {
                                $fav_act_range_ext = array_merge($fav_act_range_ext, array_keys(cat_list(intval($id), 0)));
                            }

                            if ($row1['is_gift'] == 0 && in_array($cat_id, $fav_act_range_ext)) { 
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_id'] = $row2['act_id'];
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_name'] = $row2['act_name'];
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type'] = $row2['act_type'];
                                
                                switch ($row2['act_type']) {
                                    case 0:
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满赠';
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = intval($row2['act_type_ext']);
                                        break;
                                    case 1:
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满减';
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = number_format($row2['act_type_ext'], 2);
                                        break;
                                    case 2:
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '折扣';
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = floatval($row2['act_type_ext'] / 10);
                                        break;

                                    default:
                                        break;
                                }
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['min_amount'] = $row2['min_amount'];
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext'] = intval($row2['act_type_ext']);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['cart_fav_amount'] = cart_favourable_amount($row2);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['available'] = favourable_available($row2);
                                
                                $cart_favourable = cart_favourable();
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['favourable_used'] = favourable_used($row2, $cart_favourable);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['left_gift_num'] = intval($row2['act_type_ext']) - (empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]));

                                

                                
                                if ($row2['gift']) {
                                    $merchant_goods[$key]['new_list'][$row2['act_id']]['act_gift_list'] = $row2['gift'];
                                }

                                
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_goods_list'][$row1['rec_id']] = $row1;
                            }
                            if ($row1['is_gift'] == $row2['act_id']) { 
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_cart_gift'][$row1['rec_id']] = $row1;
                            }
                        }
                        continue;
                    }
                }

                
                if (isset($sort_favourable['by_brand']) && $row1['extension_code'] != 'package_buy') {
                    
                    $get_act_range_ext = get_act_range_ext($_SESSION['user_rank'], $row['ru_id'], 2); 

                    $brand_id = $GLOBALS['db']->getOne("SELECT brand_id FROM " . $GLOBALS['ecs']->table('goods') . " WHERE goods_id = '$row1[goods_id]' ");

                    
                    $favourable_id_list = get_favourable_id($sort_favourable['by_brand']);

                    
                    if ((in_array(trim($brand_id), $get_act_range_ext) && $row1['is_gift'] == 0) || in_array($row1['is_gift'], $favourable_id_list)) {
                        foreach ($sort_favourable['by_brand'] as $key2 => $row2) {
                            $act_range_ext_str = ',' . $row2['act_range_ext'] . ',';
                            $brand_id_str = ',' . $brand_id . ',';
                            if ($row1['is_gift'] == 0 && strstr($act_range_ext_str, trim($brand_id_str))) { 
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_id'] = $row2['act_id'];
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_name'] = $row2['act_name'];
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type'] = $row2['act_type'];
                                
                                switch ($row2['act_type']) {
                                    case 0:
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满赠';
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = intval($row2['act_type_ext']);
                                        break;
                                    case 1:
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满减';
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = number_format($row2['act_type_ext'], 2);
                                        break;
                                    case 2:
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '折扣';
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = floatval($row2['act_type_ext'] / 10);
                                        break;

                                    default:
                                        break;
                                }
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['min_amount'] = $row2['min_amount'];
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext'] = intval($row2['act_type_ext']);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['cart_fav_amount'] = cart_favourable_amount($row2);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['available'] = favourable_available($row2);
                                
                                $cart_favourable = cart_favourable();
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['favourable_used'] = favourable_used($row2, $cart_favourable);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['left_gift_num'] = intval($row2['act_type_ext']) - (empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]));

                                

                                
                                if ($row2['gift']) {
                                    $merchant_goods[$key]['new_list'][$row2['act_id']]['act_gift_list'] = $row2['gift'];
                                }

                                
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_goods_list'][$row1['rec_id']] = $row1;
                            }
                            if ($row1['is_gift'] == $row2['act_id']) { 
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_cart_gift'][$row1['rec_id']] = $row1;
                            }
                        }
                        continue;
                    }
                }

                
                if (isset($sort_favourable['by_goods']) && $row1['extension_code'] != 'package_buy') {
                    $get_act_range_ext = get_act_range_ext($_SESSION['user_rank'], $row['ru_id'], 3); 

                    
                    $favourable_id_list = get_favourable_id($sort_favourable['by_goods']);

                    
                    if (in_array($row1['goods_id'], $get_act_range_ext) || in_array($row1['is_gift'], $favourable_id_list)) {
                        foreach ($sort_favourable['by_goods'] as $key2 => $row2) { 
                            $act_range_ext_str = ',' . $row2['act_range_ext'] . ','; 
                            $goods_id_str = ',' . $row1['goods_id'] . ',';
                            
                            if (strstr($act_range_ext_str, $goods_id_str) && ($row1['is_gift'] == 0)) {
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_id'] = $row2['act_id'];
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_name'] = $row2['act_name'];
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type'] = $row2['act_type'];
                                
                                switch ($row2['act_type']) {
                                    case 0:
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满赠';
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = intval($row2['act_type_ext']);
                                        break;
                                    case 1:
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '满减';
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = number_format($row2['act_type_ext'], 2);
                                        break;
                                    case 2:
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_txt'] = '折扣';
                                        $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext_format'] = floatval($row2['act_type_ext'] / 10);
                                        break;

                                    default:
                                        break;
                                }
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['min_amount'] = $row2['min_amount'];
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_type_ext'] = intval($row2['act_type_ext']);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['cart_fav_amount'] = cart_favourable_amount($row2);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['available'] = favourable_available($row2);
                                
                                $cart_favourable = cart_favourable();
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['cart_favourable_gift_num'] = empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['favourable_used'] = favourable_used($row2, $cart_favourable);
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['left_gift_num'] = intval($row2['act_type_ext']) - (empty($cart_favourable[$row2['act_id']]) ? 0 : intval($cart_favourable[$row2['act_id']]));

                                

                                
                                if ($row2['gift']) {
                                    $merchant_goods[$key]['new_list'][$row2['act_id']]['act_gift_list'] = $row2['gift'];
                                }

                                
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_goods_list'][$row1['rec_id']] = $row1;
                                break;
                            }
                            
                            if ($row1['is_gift'] == $row2['act_id']) {
                                $merchant_goods[$key]['new_list'][$row2['act_id']]['act_cart_gift'][$row1['rec_id']] = $row1;
                            }
                        }
                    } else {
                        
                        $merchant_goods[$key]['new_list'][0]['act_goods_list'][$row1['rec_id']] = $row1;
                    }
                } else {
                    
                    $merchant_goods[$key]['new_list'][0]['act_goods_list'][$row1['rec_id']] = $row1;
                }
            }
        }

        
        $merchant_goods[$key]['goods_count'] = $goods_num;
        $merchant_goods[$key]['package_goods_num'] = $package_goods_num;
    }

    return $merchant_goods;
}



function favourable_list($user_rank, $user_id = -1, $fav_id = 0)
{
    $where = '';
    if ($user_id >= 0) {
        $where .= " AND user_id = '$user_id'";
    }
    if ($fav_id > 0) {
        $where .= " AND act_id = '$fav_id' ";
    }
    
    $used_list = cart_favourable();

    
    $favourable_list = [];
    $user_rank = ',' . $user_rank . ',';
    $now = gmtime();
    $sql = "SELECT * " .
        "FROM " . $GLOBALS['ecs']->table('favourable_activity') .
        " WHERE CONCAT(',', user_rank, ',') LIKE '%" . $user_rank . "%'" .
        " AND start_time <= '$now' AND end_time >= '$now' AND review_status = 3  " . $where .
        " ORDER BY sort_order";
    $res = $GLOBALS['db']->query($sql);
    foreach ($res as $favourable) {
        $favourable['start_time'] = local_date($GLOBALS['_CFG']['time_format'], $favourable['start_time']);
        $favourable['end_time'] = local_date($GLOBALS['_CFG']['time_format'], $favourable['end_time']);
        $favourable['formated_min_amount'] = price_format($favourable['min_amount'], false);
        $favourable['formated_max_amount'] = price_format($favourable['max_amount'], false);
        $favourable['gift'] = unserialize($favourable['gift']);

        foreach ((array)$favourable['gift'] as $key => $value) {
            $favourable['gift'][$key]['formated_price'] = price_format($value['price'], false);
            
            $favourable['gift'][$key]['thumb_img'] = $GLOBALS['db']->getOne("SELECT goods_thumb FROM " . $GLOBALS['ecs']->table('goods') . " WHERE goods_id = '$value[id]'");
            $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('goods') . " WHERE is_on_sale = 1 AND goods_id = " . $value['id'];
            $is_sale = $GLOBALS['db']->getOne($sql);
            if (!$is_sale) {
                unset($favourable['gift'][$key]);
            }
        }

        $favourable['act_range_desc'] = act_range_desc($favourable);
        $favourable['act_type_desc'] = sprintf($GLOBALS['_LANG']['fat_ext'][$favourable['act_type']], $favourable['act_type_ext']);

        
        $favourable['available'] = favourable_available($favourable);
        if ($favourable['available']) {
            
            $favourable['available'] = !favourable_used($favourable, $used_list);
        }

        $favourable_list[] = $favourable;
    }

    return $favourable_list;
}


function cart_favourable()
{
    
    if (!empty($_SESSION['user_id'])) {
        $sess_id = " user_id = '" . $_SESSION['user_id'] . "' ";
    } else {
        $sess_id = " session_id = '" . real_cart_mac_ip() . "' ";
    }
    

    $list = [];
    $sql = "SELECT is_gift, COUNT(*) AS num " .
        "FROM " . $GLOBALS['ecs']->table('cart') .
        " WHERE " . $sess_id .
        " AND rec_type = '" . CART_GENERAL_GOODS . "'" .
        " AND is_gift > 0" .
        " GROUP BY is_gift";
    $res = $GLOBALS['db']->query($sql);
    foreach ($res as $row) {
        $list[$row['is_gift']] = $row['num'];
    }

    return $list;
}


function sort_favourable($favourable_list)
{
    $arr = [];
    foreach ($favourable_list as $key => $value) {
        switch ($value['act_range']) {
            case FAR_ALL:
                $arr['by_all'][$key] = $value;
                break;
            case FAR_CATEGORY:
                $arr['by_category'][$key] = $value;
                break;
            case FAR_BRAND:
                $arr['by_brand'][$key] = $value;
                break;
            case FAR_GOODS:
                $arr['by_goods'][$key] = $value;
                break;
            default:
                break;
        }
    }
    return $arr;
}


function act_range_desc($favourable)
{
    if ($favourable['act_range'] == FAR_BRAND) {
        $sql = "SELECT brand_name FROM " . $GLOBALS['ecs']->table('brand') .
            " WHERE brand_id " . db_create_in($favourable['act_range_ext']);
        return join(',', $GLOBALS['db']->getCol($sql));
    } elseif ($favourable['act_range'] == FAR_CATEGORY) {
        $sql = "SELECT cat_name FROM " . $GLOBALS['ecs']->table('category') .
            " WHERE cat_id " . db_create_in($favourable['act_range_ext']);
        return join(',', $GLOBALS['db']->getCol($sql));
    } elseif ($favourable['act_range'] == FAR_GOODS) {
        $sql = "SELECT goods_name FROM " . $GLOBALS['ecs']->table('goods') .
            " WHERE goods_id " . db_create_in($favourable['act_range_ext']);
        return join(',', $GLOBALS['db']->getCol($sql));
    } else {
        return '';
    }
}


function favourable_available($favourable)
{
    
    $user_rank = $_SESSION['user_rank'];
    if (strpos(',' . $favourable['user_rank'] . ',', ',' . $user_rank . ',') === false) {
        return false;
    }

    
    $amount = cart_favourable_amount($favourable);

    
    return $amount >= $favourable['min_amount'] &&
        ($amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0);
}


function cart_favourable_amount($favourable)
{
    
    if (!empty($_SESSION['user_id'])) {
        $c_sess = " c.user_id = '" . $_SESSION['user_id'] . "' ";
    } else {
        $c_sess = " c.session_id = '" . real_cart_mac_ip() . "' ";
    }

    $fav_where = "";
    if ($favourable['userFav_type'] == 0) {
        $fav_where = " AND g.user_id = '" . $favourable['user_id'] . "' ";
    }
    

    
    $sql = "SELECT SUM(c.goods_price * c.goods_number) " .
        "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " . $GLOBALS['ecs']->table('goods') . " AS g " .
        "WHERE c.goods_id = g.goods_id " .
        "AND " . $c_sess . " AND c.rec_type = '" . CART_GENERAL_GOODS . "' " .
        "AND c.is_gift = 0 " .
        "AND c.goods_id > 0 " . $fav_where; 

    
    if ($favourable['act_range'] == FAR_ALL) {
        
    } elseif ($favourable['act_range'] == FAR_CATEGORY) {
        
        $id_list = [];
        $cat_list = explode(',', $favourable['act_range_ext']);
        foreach ($cat_list as $id) {
            $id_list = array_merge($id_list, array_keys(cat_list(intval($id), 0)));
        }

        $sql .= "AND g.cat_id " . db_create_in($id_list);
    } elseif ($favourable['act_range'] == FAR_BRAND) {
        $id_list = explode(',', $favourable['act_range_ext']);

        $sql .= "AND g.brand_id " . db_create_in($id_list);
    } else {
        $id_list = explode(',', $favourable['act_range_ext']);

        $sql .= "AND g.goods_id " . db_create_in($id_list);
    }

    
    return $GLOBALS['db']->getOne($sql);
}



function flow_available_points($cart_value)
{
    
    if (!empty($_SESSION['user_id'])) {
        $c_sess = " c.user_id = '" . $_SESSION['user_id'] . "' ";
    } else {
        $c_sess = " c.session_id = '" . real_cart_mac_ip() . "' ";
    }
    
    $where = "";
    if (!empty($cart_value)) {
        $where = " AND c.rec_id " . db_create_in($cart_value);
    }

    $sql = "SELECT SUM(g.integral * c.goods_number) " .
        "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " . $GLOBALS['ecs']->table('goods') . " AS g " .
        "WHERE " . $c_sess . " AND c.goods_id = g.goods_id AND c.is_gift = 0 AND g.integral > 0 $where" .
        "AND c.rec_type = '" . CART_GENERAL_GOODS . "'";

    $val = intval($GLOBALS['db']->getOne($sql));

    return integral_of_value($val);
}


function update_team($team_id = 0, $team_parent_id = 0)
{
    if ($team_id > 0) {
        $sql = "select g.id,g.goods_id,g.team_price,g.limit_num, g.team_num,g.validity_time,og.goods_name from " . $GLOBALS['ecs']->table('team_log') . " as tl LEFT JOIN " . $GLOBALS['ecs']->table('team_goods') . " as g ON tl.t_id = g.id LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " as og ON tl.goods_id = og.goods_id  where tl.team_id =$team_id ";
        $res = $GLOBALS['db']->getRow($sql);
        
        $sql = "SELECT count(order_id) as num  FROM " . $GLOBALS['ecs']->table('order_info') . " WHERE team_id = '" . $team_id . "' AND extension_code = 'team_buy'  and pay_status = '" . PS_PAYED . "' ";
        $team_count = $GLOBALS['db']->getRow($sql);
        if ($team_count['num'] >= $res['team_num']) {
            $sql = 'UPDATE ' . $GLOBALS['ecs']->table('team_log') .
                " SET status = '1' " .
                " WHERE team_id = '" . $team_id . "' ";
            $GLOBALS['db']->query($sql);
            
            if (is_dir(APP_WECHAT_PATH)) {
                $sql = 'SELECT order_sn,user_id FROM ' . $GLOBALS['ecs']->table('order_info') . " WHERE team_id = '" . $team_id . "' AND extension_code = 'team_buy'  and pay_status = '" . PS_PAYED . "' ";
                $team_order = $GLOBALS['db']->query($sql);
                foreach ($team_order as $key => $vo) {
                    $pushData = [
                        'keyword1' => ['value' => $vo['order_sn'], 'color' => '#173177'],
                        'keyword2' => ['value' => $res['goods_name'], 'color' => '#173177']
                    ];
                    $order_url = __HOST__ . url('team/goods/teamwait', ['team_id' => $team_id]);
                    $url = str_replace('public/notify/wxpay.php', 'index.php', $order_url);
                    push_template('OPENTM407456411', $pushData, $url, $vo['user_id']);
                }
            }
        }
        
        $limit_num = $res['limit_num'] + 1;
        $sql = 'UPDATE ' . $GLOBALS['ecs']->table('team_goods') . " SET limit_num = '$limit_num' " . " WHERE goods_id = '" . $res['goods_id'] . "' and id = '" . $res['id'] . "' ";
        $GLOBALS['db']->query($sql);
        
        if (is_dir(APP_WECHAT_PATH)) {
            if ($team_parent_id > 0) {
                $pushData = [
                    'keyword1' => ['value' => $res['goods_name'], 'color' => '#173177'],
                    'keyword2' => ['value' => $res['team_price'] . '元', 'color' => '#173177'],
                    'keyword3' => ['value' => $res['team_num'], 'color' => '#173177'],
                    'keyword4' => ['value' => '普通', 'color' => '#173177'],
                    'keyword5' => ['value' => $res['validity_time'] . '小时', 'color' => '#173177']
                ];
                logResult(var_export($pushData, true));
                $order_url = __HOST__ . url('team/goods/teamwait', ['team_id' => $team_id]);
                $url = str_replace('public/notify/wxpay.php', 'index.php', $order_url);
                push_template('OPENTM407307456', $pushData, $url, $_SESSION['user_id']);
            } else {
                $pushData = [
                    'first' => ['value' => '您好，您已参团成功'],
                    'keyword1' => ['value' => $res['goods_name'], 'color' => '#173177'],
                    'keyword2' => ['value' => $res['team_price'] . '元', 'color' => '#173177'],
                    'keyword3' => ['value' => $res['validity_time'] . '小时', 'color' => '#173177']
                ];
                $order_url = __HOST__ . url('team/goods/teamwait', ['team_id' => $team_id]);
                $url = str_replace('public/notify/wxpay.php', 'index.php', $order_url);
                push_template('OPENTM400048581', $pushData, $url, $_SESSION['user_id']);
            }
        }
    }
}


function get_user_region_address($order_id = 0, $address = '', $type = 0)
{
    if ($type == 1) {
        $table = 'order_return';
        $where = "o.ret_id = '$order_id'";
    } else {
        $table = 'order_info';
        $where = "o.order_id = '$order_id'";
    }

    
    $sql = "SELECT concat(IFNULL(p.region_name, ''), " .
        "'  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, ''), '  ', IFNULL(s.region_name, '')) AS region " .
        "FROM " . $GLOBALS['ecs']->table($table) . " AS o " .
        
        "LEFT JOIN " . $GLOBALS['ecs']->table('region') . " AS p ON o.province = p.region_id " .
        "LEFT JOIN " . $GLOBALS['ecs']->table('region') . " AS t ON o.city = t.region_id " .
        "LEFT JOIN " . $GLOBALS['ecs']->table('region') . " AS d ON o.district = d.region_id " .
        "LEFT JOIN " . $GLOBALS['ecs']->table('region') . " AS s ON o.street = s.region_id " .
        "WHERE " . $where;
    $region = $GLOBALS['db']->getOne($sql);

    if ($address) {
        $region = $region . "&nbsp;" . $address;
    }

    return $region;
}



function categroy_number($tc_id = 0)
{
    if ($tc_id > 0) {
        $tc_id = categroy_id($tc_id);
        $where .= " and tc_id in ($tc_id) ";
    }
    $sql = "SELECT count(id) as num FROM {pre}team_goods WHERE is_team = 1 $where ";
    $goods_number = $GLOBALS['db']->getOne($sql);
    return $goods_number;
}


function categroy_id($tc_id = 0)
{
    $one = dao('team_category')->field('id')->where('id =' . $tc_id . ' or parent_id=' . $tc_id)->select();
    if ($one) {
        foreach ($one as $key) {
            $one_id[] = $key['id'];
        }
        $tc_id = implode(',', $one_id);
    }
    return $tc_id;
}


function set_default_filter($goods_id = 0, $cat_id = 0)
{
    
    if ($cat_id > 0) {
        $parent_cat_list = get_select_category($cat_id, 1, true);
        $filter_category_navigation = get_array_category_info($parent_cat_list);
        $GLOBALS['smarty']->assign('filter_category_navigation', $filter_category_navigation);
    }

    $GLOBALS['smarty']->assign('filter_category_list', get_category_list($cat_id)); 
    $GLOBALS['smarty']->assign('filter_brand_list', search_brand_list($goods_id)); 

    return true;
}


function get_array_category_info($arr = [])
{
    if ($arr) {
        $sql = " SELECT cat_id, cat_name FROM " . $GLOBALS['ecs']->table('category') . " WHERE cat_id " . db_create_in($arr);
        return $GLOBALS['db']->getAll($sql);
    } else {
        return false;
    }
}




function get_category_list($cat_id = 0, $relation = 0)
{
    if ($relation == 0) {
        $parent_id = $GLOBALS['db']->getOne(" SELECT parent_id FROM " . $GLOBALS['ecs']->table('category') . " WHERE cat_id = '$cat_id' ");
    } elseif ($relation == 1) {
        $parent_id = $GLOBALS['db']->getOne(" SELECT parent_id FROM " . $GLOBALS['ecs']->table('category') . " WHERE cat_id = '$cat_id' ");
    } elseif ($relation == 2) {
        $parent_id = $cat_id;
    }

    $parent_id = empty($parent_id) ? 0 : $parent_id;
    $category_list = $GLOBALS['db']->getAll(" SELECT cat_id, cat_name FROM " . $GLOBALS['ecs']->table('category') . " WHERE parent_id = '$parent_id' ");
    foreach ($category_list as $key => $val) {
        if ($cat_id == $val['cat_id']) {
            $is_selected = 1;
        } else {
            $is_selected = 0;
        }
        $category_list[$key]['is_selected'] = $is_selected;
    }
    return $category_list;
}


function search_brand_list($goods_id = 0)
{
    $letter = empty($_REQUEST['letter']) ? "" : trim($_REQUEST['letter']);
    $keyword = empty($_REQUEST['keyword']) ? "" : trim($_REQUEST['keyword']);

    $where = "";
    
    

    if ($goods_id > 0) {
        $sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('goods') . " where goods_id = '$goods_id'";
        $adminru['ru_id'] = $GLOBALS['db']->getOne($sql);
    }

    if (!empty($keyword)) {
        $where .= " AND (brand_name LIKE '%" . mysql_like_quote($keyword) . "%' OR brand_letter LIKE '%" . mysql_like_quote($keyword) . "%') ";
    }
    $sql = 'SELECT brand_id, brand_name FROM ' . $GLOBALS['ecs']->table('brand') . ' WHERE 1 ' . $where . ' ORDER BY sort_order';
    $res = $GLOBALS['db']->getAll($sql);

    $brand_list = [];
    foreach ($res as $key => $val) {
        
        $is_selected = 0;
        $res[$key]['is_selected'] = $is_selected;
        $res[$key]['letter'] = !empty($val['brand_name']) ? getFirstCharter($val['brand_name']) : '';
        $res[$key]['brand_name'] = !empty($val['brand_name']) ? addslashes($val['brand_name']) : '';
        if (!empty($letter)) {
            if ($letter == "QT" && !$res[$key]['letter']) {
                $brand_list[] = $res[$key];
            } elseif ($letter == $res[$key]['letter']) {
                $brand_list[] = $res[$key];
            }
        } else {
            $brand_list[] = $res[$key];
        }
    }

    return $brand_list;
}


function getFirstCharter($str)
{
    if (empty($str)) {
        return '';
    }
    $fchar = ord($str{0});
    if ($fchar >= ord('A') && $fchar <= ord('z')) {
        return strtoupper($str{0});
    }
    $s1 = iconv('UTF-8', 'gb2312', $str);
    $s2 = iconv('gb2312', 'UTF-8', $s1);
    $s = $s2 == $str ? $s1 : $str;
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= -20319 && $asc <= -20284) {
        return 'A';
    }
    if ($asc >= -20283 && $asc <= -19776) {
        return 'B';
    }
    if ($asc >= -19775 && $asc <= -19219) {
        return 'C';
    }
    if ($asc >= -19218 && $asc <= -18711) {
        return 'D';
    }
    if ($asc >= -18710 && $asc <= -18527) {
        return 'E';
    }
    if ($asc >= -18526 && $asc <= -18240) {
        return 'F';
    }
    if ($asc >= -18239 && $asc <= -17923) {
        return 'G';
    }
    if ($asc >= -17922 && $asc <= -17418) {
        return 'H';
    }
    if ($asc >= -17417 && $asc <= -16475) {
        return 'J';
    }
    if ($asc >= -16474 && $asc <= -16213) {
        return 'K';
    }
    if ($asc >= -16212 && $asc <= -15641) {
        return 'L';
    }
    if ($asc >= -15640 && $asc <= -15166) {
        return 'M';
    }
    if ($asc >= -15165 && $asc <= -14923) {
        return 'N';
    }
    if ($asc >= -14922 && $asc <= -14915) {
        return 'O';
    }
    if ($asc >= -14914 && $asc <= -14631) {
        return 'P';
    }
    if ($asc >= -14630 && $asc <= -14150) {
        return 'Q';
    }
    if ($asc >= -14149 && $asc <= -14091) {
        return 'R';
    }
    if ($asc >= -14090 && $asc <= -13319) {
        return 'S';
    }
    if ($asc >= -13318 && $asc <= -12839) {
        return 'T';
    }
    if ($asc >= -12838 && $asc <= -12557) {
        return 'W';
    }
    if ($asc >= -12556 && $asc <= -11848) {
        return 'X';
    }
    if ($asc >= -11847 && $asc <= -11056) {
        return 'Y';
    }
    if ($asc >= -11055 && $asc <= -10247) {
        return 'Z';
    }
    return null;
}




function get_select_category($cat_id = 0, $relation = 0, $self = true)
{
    
    static $cat_list = [];
    $cat_list[] = intval($cat_id);

    if ($relation == 0) {
        return $cat_list;
    } elseif ($relation == 1) {
        $sql = " select parent_id from " . $GLOBALS['ecs']->table('category') . " where cat_id='" . $cat_id . "' ";
        $parent_id = $GLOBALS['db']->getOne($sql);
        if (!empty($parent_id)) {
            get_select_category($parent_id, $relation, $self);
        }
        
        if ($self == false) {
            unset($cat_list[0]);
        }
        $cat_list[] = 0;
        
        return array_reverse(array_unique($cat_list));
    } elseif ($relation == 2) {
        $sql = " select cat_id from " . $GLOBALS['ecs']->table('category') . " where parent_id='" . $cat_id . "' ";
        $child_id = $GLOBALS['db']->getCol($sql);
        if (!empty($child_id)) {
            foreach ($child_id as $key => $val) {
                get_select_category($val, $relation, $self);
            }
        }
        
        if ($self == false) {
            unset($cat_list[0]);
        }
        return $cat_list;
    }
}


function get_attr_value($goods_id, $attr_id)
{
    $sql = "select * from " . $GLOBALS['ecs']->table('goods_attr') . " where goods_id='$goods_id' and goods_attr_id='$attr_id'";
    $re = $GLOBALS['db']->getRow($sql);

    if (!empty($re)) {
        return $re;
    } else {
        return false;
    }
}

function team_get_tree($tree_id = 0)
{
    $three_arr = [];
    $where = "";
    $sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('team_category') . " WHERE parent_id = '$tree_id' AND status = 1" . $where;
    if ($GLOBALS['db']->getOne($sql) || $tree_id == 0) {
        $child_sql = 'SELECT id, name, parent_id,status ' . ' FROM ' . $GLOBALS['ecs']->table('team_category') .
            " WHERE parent_id = '$tree_id' AND status = 1 " . $where . " ORDER BY sort_order ASC, id ASC";
        $res = $GLOBALS['db']->getAll($child_sql);
        foreach ($res as $k => $row) {
            if ($row['status']) {
                $three_arr[$k]['tc_id'] = $row['id'];
                $three_arr[$k]['name'] = $row['name'];
            }
            if (isset($row['id'])) {
                $child_tree = team_get_tree($row['id']);
                if ($child_tree) {
                    $three_arr[$k]['id'] = $child_tree;
                }
            }
        }
    }
    return $three_arr;
}


function flow_cart_stock($arr, $store_id = 0)
{
    
    if (!empty($_SESSION['user_id'])) {
        $sess_id = " user_id = '" . $_SESSION['user_id'] . "' ";
    } else {
        $sess_id = " session_id = '" . real_cart_mac_ip() . "' ";
    }
    

    foreach ($arr as $key => $val) {
        $val = intval(make_semiangle($val));
        if ($val <= 0 || !is_numeric($key)) {
            continue;
        }

        $sql = "SELECT `goods_id`, `goods_attr_id`, `extension_code`, `warehouse_id` FROM" . $GLOBALS['ecs']->table('cart') .
            " WHERE rec_id='$key' AND " . $sess_id;
        $goods = $GLOBALS['db']->getRow($sql);

        $sql = "SELECT g.goods_name, g.goods_number, g.goods_id, c.product_id, g.model_attr " .
            "FROM " . $GLOBALS['ecs']->table('goods') . " AS g, " .
            $GLOBALS['ecs']->table('cart') . " AS c " .
            "WHERE g.goods_id = c.goods_id AND c.rec_id = '$key'";
        $row = $GLOBALS['db']->getRow($sql);

        
        $sql = "select IF(g.model_inventory < 1, g.goods_number, IF(g.model_inventory < 2, wg.region_number, wag.region_number)) AS goods_number " .
            " from " . $GLOBALS['ecs']->table('goods') . " as g " .
            " left join " . $GLOBALS['ecs']->table('warehouse_goods') . " as wg on g.goods_id = wg.goods_id" .
            " left join " . $GLOBALS['ecs']->table('warehouse_area_goods') . " as wag on g.goods_id = wag.goods_id" .
            " where g.goods_id = '" . $row['goods_id'] . "'";
        $goods_number = $GLOBALS['db']->getOne($sql);

        $row['goods_number'] = $goods_number;
        

        
        if (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] != 'package_buy' && $store_id == 0) {
            
            
            $row['product_id'] = trim($row['product_id']);
            if (!empty($row['product_id'])) {
                
                if ($row['model_attr'] == 1) {
                    $table_products = "products_warehouse";
                } elseif ($row['model_attr'] == 2) {
                    $table_products = "products_area";
                } else {
                    $table_products = "products";
                }
                

                $sql = "SELECT product_number FROM " . $GLOBALS['ecs']->table($table_products) . " WHERE goods_id = '" . $row['goods_id'] . "' and product_id = '" . $row['product_id'] . "'";
                $product_number = $GLOBALS['db']->getOne($sql);
                if ($product_number < $val) {
                    show_message(sprintf(
                        L('stock_insufficiency'),
                        $row['goods_name'],
                        $product_number,
                        $product_number
                    ));
                    exit;
                }
            } else {
                if ($row['goods_number'] < $val) {
                    show_message(sprintf(
                        L('stock_insufficiency'),
                        $row['goods_name'],
                        $row['goods_number'],
                        $row['goods_number']
                    ));
                    exit;
                }
            }
            
        } elseif (intval($GLOBALS['_CFG']['use_storage']) > 0 && $store_id > 0) {
            $sql = "SELECT goods_number,ru_id FROM" . $GLOBALS['ecs']->table("store_goods") . " WHERE store_id = '$store_id' AND goods_id = '" . $row['goods_id'] . "' ";
            $goodsInfo = $GLOBALS['db']->getRow($sql);

            $products = get_warehouse_id_attr_number($row['goods_id'], $goods['goods_attr_id'], $goodsInfo['ru_id'], 0, 0, '', $store_id);

            $attr_number = $products['product_number'];
            if ($goods['goods_attr_id']) { 
                $row['goods_number'] = $attr_number;
            } else {
                $row['goods_number'] = $goodsInfo['goods_number'];
            }
            if ($row['goods_number'] < $val) {
                show_message(sprintf(
                    L('stock_store_shortage'),
                    $row['goods_name'],
                    $row['goods_number'],
                    $row['goods_number']
                ));
                exit;
            }
        } elseif (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] == 'package_buy') {
            if (judge_package_stock($goods['goods_id'], $val)) {
                show_message(L('package_stock_insufficiency'));
                exit;
            }
        }
    }
}


function team_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0)
{
    static $res = null;
    if ($res === null) {
        $data = read_static_cache('team_cat_pid_releate');
        if ($data === false) {
            $sql = "SELECT c.*, COUNT(s.id) AS has_children" .
                " FROM {pre}team_category AS c " .
                " LEFT JOIN {pre}team_category  AS s ON s.parent_id=c.id" .
                " where c.status = 1" .
                " GROUP BY c.id " .
                " ORDER BY parent_id, sort_order DESC";
            $res = $GLOBALS['db']->query($sql);
            write_static_cache('team_cat_pid_releate', $res);
        } else {
            $res = $data;
        }
    }

    if (empty($res) == true) {
        return $re_type ? '' : [];
    }

    $options = team_cat_options($cat_id, $res); 

    
    if ($level > 0) {
        if ($cat_id == 0) {
            $end_level = $level;
        } else {
            $first_item = reset($options); 
            $end_level = $first_item['level'] + $level;
        }

        
        foreach ($options as $key => $val) {
            if ($val['level'] >= $end_level) {
                unset($options[$key]);
            }
        }
    }

    $pre_key = 0;
    foreach ($options as $key => $value) {
        $options[$key]['has_children'] = 1;
        if ($pre_key > 0) {
            if ($options[$pre_key]['id'] == $options[$key]['parent_id']) {
                $options[$pre_key]['has_children'] = 1;
            }
        }
        $pre_key = $key;
    }

    if ($re_type == true) {
        $select = '';
        foreach ($options as $var) {
            $select .= '<option value="' . $var['id'] . '" ';
            
            $select .= ($selected == $var['id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0) {
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars(addslashes($var['name'])) . '</option>';
        }

        return $select;
    } else {
        foreach ($options as $key => $value) {
            $options[$key]['url'] = build_uri('article_cat', ['acid' => $value['cat_id']], $value['cat_name']);
        }
        return $options;
    }
}


function team_cat_options($spec_cat_id, $arr)
{
    static $cat_options = [];
    if (isset($cat_options[$spec_cat_id])) {
        return $cat_options[$spec_cat_id];
    }

    if (!isset($cat_options[0])) {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = [];
        while (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $cat_id = $value['id'];
                if ($level == 0 && $last_cat_id == 0) {
                    if ($value['parent_id'] > 0) {
                        break;
                    }
                    $options[$cat_id] = $value;
                    $options[$cat_id]['level'] = $level;
                    $options[$cat_id]['id'] = $cat_id;
                    $options[$cat_id]['name'] = $value['name'];
                    unset($arr[$key]);

                    if ($value['has_children'] == 0) {
                        continue;
                    }
                    $last_cat_id = $cat_id;

                    $cat_id_array = [$cat_id];
                    $level_array[$last_cat_id] = ++$level;
                    continue;
                }

                if ($value['parent_id'] == $last_cat_id) {
                    $options[$cat_id] = $value;
                    $options[$cat_id]['level'] = $level;
                    $options[$cat_id]['id'] = $cat_id;
                    $options[$cat_id]['name'] = $value['name'];

                    unset($arr[$key]);

                    if ($value['has_children'] > 0) {
                        if (end($cat_id_array) != $last_cat_id) {
                            $cat_id_array[] = $last_cat_id;
                        }
                        $last_cat_id = $cat_id;
                        $cat_id_array[] = $cat_id;
                        $level_array[$last_cat_id] = ++$level;
                    }
                } elseif ($value['parent_id'] > $last_cat_id) {
                    break;
                }
            }

            $count = count($cat_id_array);
            if ($count > 1) {
                $last_cat_id = array_pop($cat_id_array);
            } elseif ($count == 1) {
                if ($last_cat_id != end($cat_id_array)) {
                    $last_cat_id = end($cat_id_array);
                } else {
                    $level = 0;
                    $last_cat_id = 0;
                    $cat_id_array = [];
                    continue;
                }
            }

            if ($last_cat_id && isset($level_array[$last_cat_id])) {
                $level = $level_array[$last_cat_id];
            } else {
                $level = 0;
            }
        }
        $cat_options[0] = $options;
    } else {
        $options = $cat_options[0];
    }

    if (!$spec_cat_id) {
        return $options;
    } else {
        if (empty($options[$spec_cat_id])) {
            return [];
        }

        $spec_cat_id_level = $options[$spec_cat_id]['level'];

        foreach ($options as $key => $value) {
            if ($key != $spec_cat_id) {
                unset($options[$key]);
            } else {
                break;
            }
        }

        $spec_cat_id_array = [];
        foreach ($options as $key => $value) {
            if (($spec_cat_id_level == $value['level'] && $value['id'] != $spec_cat_id) ||
                ($spec_cat_id_level > $value['level'])) {
                break;
            } else {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $cat_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;
    }
}




function team_log_account_change($user_id, $shop_money = 0, $frozen_money = 0, $rank_points = 0, $pay_points = 0, $change_desc = '', $change_type)
{
    if ($change_type == ACT_TRANSFERRED) {
        
        $account_log = [
            'user_id'       => $user_id,
            'user_money'    => -$shop_money,
            'frozen_money'  => $frozen_money,
            'rank_points'   => -$rank_points,
            'pay_points'    => -$pay_points,
            'change_time'   => gmtime(),
            'change_desc'   => $change_desc,
            'change_type'   => $change_type
        ];
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('account_log'), $account_log, 'INSERT');
    }
    if ($change_type == ACT_TRANSFERRED) {
        
        $sql = "UPDATE " . $GLOBALS['ecs']->table('users') .
            " SET user_money = user_money - ('$shop_money')," .
            " frozen_money = frozen_money - ('$frozen_money')," .
            " rank_points = rank_points - ('$rank_points')," .
            " pay_points = pay_points - ('$pay_points')" .
            " WHERE user_id = '$user_id' LIMIT 1";
        $GLOBALS['db']->query($sql);
    }
}




function team_order_action_change($order_id, $action_user = 'admin',$order_status = 0, $shipping_status = 0, $pay_status = 0, $action_note = '')
{
    
    $action_log = [
        'order_id'       => $order_id,
        'action_user'    => $action_user,
        'order_status'  => $order_status,
        'shipping_status'   => $shipping_status,
        'pay_status'    => $pay_status,
        'action_note'   => $action_note,
        'log_time'   => gmtime()
    ];
    $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_action'), $action_log, 'INSERT');

    
    $sql = "UPDATE " . $GLOBALS['ecs']->table('order_info') .
        " SET order_status = $order_status," .
        " shipping_status = $shipping_status," .
        " pay_status = $pay_status" .
        " WHERE order_id = '$order_id' LIMIT 1";
    $GLOBALS['db']->query($sql);

}