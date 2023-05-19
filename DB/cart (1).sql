-- 商品主要表
INSERT INTO
    `shop_pro` (
        `pro_sid`,
        `cat_sid`,
        `catDet_sid`,
        `sup_sid`,
        `pro_for`,
        `pro_name`,
        `pro_describe`,
        `pro_img`,
        `pro_onWeb`,
        `pro_update`,
        `pro_status`
    )
VALUES
    (
        'P001',
        'F',
        'CA',
        1,
        'D',
        'Dog Food A',
        'This is a dog food.',
        'dog_food_a.jpg',
        NOW(),
        NOW(),
        1
    ),
    (
        'P002',
        'F',
        'FE',
        2,
        'C',
        'Cat Food B',
        'This is a cat food.',
        'cat_food_b.jpg',
        NOW(),
        NOW(),
        1
    ),
    (
        'P003',
        'F',
        'HE',
        1,
        'B',
        'Pet Health C',
        'This is a pet health product.',
        'pet_health_c.jpg',
        NOW(),
        NOW(),
        1
    ),
    (
        'P004',
        'F',
        'SN',
        2,
        'D',
        'Dog Snack D',
        'This is a dog snack.',
        'dog_snack_d.jpg',
        NOW(),
        NOW(),
        1
    ),
    (
        'P005',
        'G',
        'CL',
        1,
        'C',
        'Cat Cleaning E',
        'This is a cat cleaning product.',
        'cat_cleaning_e.jpg',
        NOW(),
        NOW(),
        1
    ),
    (
        'P006',
        'G',
        'DR',
        2,
        'B',
        'Pet Dress F',
        'This is a pet dress.',
        'pet_dress_f.jpg',
        NOW(),
        NOW(),
        1
    ),
    (
        'P007',
        'G',
        'EA',
        1,
        'D',
        'Dog Utensils G',
        'This is a dog utensil.',
        'dog_utensil_g.jpg',
        NOW(),
        NOW(),
        1
    ),
    (
        'P008',
        'G',
        'OD',
        2,
        'C',
        'Cat Outdoor H',
        'This is a cat outdoor product.',
        'cat_outdoor_h.jpg',
        NOW(),
        NOW(),
        1
    ),
    (
        'P009',
        'G',
        'OT',
        1,
        'B',
        'Pet Other I',
        'This is a pet other product.',
        'pet_other_i.jpg',
        NOW(),
        NOW(),
        1
    ),
    (
        'P010',
        'G',
        'TO',
        2,
        'D',
        'Dog Toy J',
        'This is a dog toy.',
        'dog_toy_j.jpg',
        NOW(),
        NOW(),
        1
    );

-- 商品子資料表
INSERT INTO
    `shop_prodet` (
        `proDet_sid`,
        `pro_sid`,
        `proDet_name`,
        `proDet_price`,
        `proDet_qty`,
        `proDet_img`,
        `pro_forAge`
    )
VALUES
    (
        'PD001',
        'P001',
        'Dog Food A Large',
        500,
        100,
        'dog_food_a_large.jpg',
        004
    ),
    (
        'PD002',
        'P001',
        'Dog Food A Medium',
        400,
        100,
        'dog_food_a_medium.jpg',
        002
    ),
    (
        'PD003',
        'P001',
        'Dog Food A Small',
        300,
        100,
        'dog_food_a_small.jpg',
        001
    ),
    (
        'PD004',
        'P002',
        'Cat Food B Large',
        450,
        100,
        'cat_food_b_large.jpg',
        004
    ),
    (
        'PD005',
        'P002',
        'Cat Food B Medium',
        350,
        100,
        'cat_food_b_medium.jpg',
        002
    ),
    (
        'PD006',
        'P002',
        'Cat Food B Small',
        250,
        100,
        'cat_food_b_small.jpg',
        001
    ),
    (
        'PD007',
        'P003',
        'Pet Health C Large',
        600,
        100,
        'pet_health_c_large.jpg',
        004
    ),
    (
        'PD008',
        'P003',
        'Pet Health C Medium',
        500,
        100,
        'pet_health_c_medium.jpg',
        002
    ),
    (
        'PD009',
        'P003',
        'Pet Health C Small',
        400,
        100,
        'pet_health_c_small.jpg',
        001
    ),
    (
        'PD010',
        'P004',
        'Dog Snack D Large',
        350,
        100,
        'dog_snack_d_large.jpg',
        004
    );

-- 商品查詢SQL
/*
 SELECT 
 sp.`pro_sid`, 
 sp.`pro_name`, 
 spd.`proDet_sid`, 
 spd.`proDet_name`,
 spd.`proDet_price`, 
 spd.`proDet_qty`, 
 FROM 
 `shop_pro` sp
 JOIN 
 `shop_prodet` spd ON sp.`pro_sid` = spd.`pro_sid`;
 
 
 -- 活動時段基本查詢SQL
 SELECT 
 ai.`act_sid`, 
 ai.`act_name`, 
 ag.`group_date`, 
 ag.`ppl_max` as 'maximum_people',
 ag.`price_adult`,
 ag.`price_kid`,
 CASE 
 WHEN ag.`group_time` = 0 THEN '上午'
 WHEN ag.`group_time` = 1 THEN '下午'
 WHEN ag.`group_time` = 2 THEN '全天'
 END as 'group_time'
 FROM 
 `act_info` ai
 JOIN 
 `act_group` ag ON ai.`act_sid` = ag.`act_sid`
 WHERE
 ag.`group_status` = 1; -- 只查詢開放報名的活動
 */
-- 隨機抽兩筆商品 新增購物車
INSERT INTO
    `ord_cart` (
        `member_sid`,
        `relType`,
        `rel_sid`,
        `rel_seqNum_sid`,
        `prodQty`,
        `orderStatus`
    )
SELECT
    'mem00001',
    -- 會員編號，這裡只是示範，請替換成實際的會員編號
    'PROD',
    `pro_sid`,
    `prodet_sid`,
    1,
    -- 購買數量，這裡只是示範，請根據實際情況設定
    '001'
FROM
    (
        SELECT
            `pro_sid`,
            `prodet_sid`
        FROM
            `shop_pro`
            JOIN `shop_prodet` USING(`pro_sid`)
        ORDER BY
            RAND()
        LIMIT
            2
    ) AS subquery;

-- 隨機抽兩筆活動 新增購物車
INSERT INTO
    `ord_cart` (
        `member_sid`,
        `relType`,
        `rel_sid`,
        `rel_seqNum_sid`,
        `adultQty`,
        `childQty`,
        `orderStatus`
    )
SELECT
    'mem00001',
    -- 會員編號，這裡只是示範，請替換成實際的會員編號
    'EVENT',
    `act_sid`,
    `group_sid`,
    1,
    -- 成人數量，這裡只是示範，請根據實際情況設定
    1,
    -- 小孩數量，這裡只是示範，請根據實際情況設定
    '001'
FROM
    (
        SELECT
            `act_sid`,
            `group_sid`
        FROM
            `act_info`
            JOIN `act_group` USING(`act_sid`)
        ORDER BY
            RAND()
        LIMIT
            2
    ) AS subquery;

-- 查詢會員商品購物車
SELECT
    *
FROM
    ord_cart
WHERE
    member_sid = 'mem00001';

/*
 SELECT 
 `member_sid`, 
 `relType`, 
 `rel_sid`, 
 `rel_seqNum_sid`, 
 `rel_name`, 
 `rel_desc`, 
 `prod_price`,
 `prodQty`, 
 `price_adult`,
 `adultQty`, 
 `price_kid`,
 `childQty`, 
 `group_date`, 
 `group_time`, 
 `orderStatus`
 FROM
 (
 SELECT 
 c.`member_sid`, 
 c.`relType`, 
 c.`rel_sid`, 
 c.`rel_seqNum_sid`, 
 p.`pro_name` AS `rel_name`, 
 d.`proDet_name` AS `rel_desc`, 
 d.proDet_price AS `prod_price`,
 c.`prodQty`, 
 NULL AS `price_adult`,
 c.`adultQty`, 
 NULL AS `price_kid`,
 c.`childQty`, 
 NULL AS `group_date`, 
 NULL AS `group_time`, 
 c.`orderStatus`
 FROM `ord_cart` AS c
 JOIN `shop_pro` AS p ON c.`rel_sid` = p.`pro_sid`
 JOIN `shop_prodet` AS d ON c.`rel_seqNum_sid` = d.`prodet_sid`
 WHERE c.`relType` = 'PROD' AND c.member_sid = 'mem00001'
 
 UNION ALL
 
 SELECT 
 c.`member_sid`, 
 c.`relType`, 
 c.`rel_sid`, 
 c.`rel_seqNum_sid`, 
 i.`act_name` AS `rel_name`, 
 i.`act_content` AS `rel_desc`, 
 NULL AS `prod_price`,
 c.`prodQty`, 
 g.price_adult,
 c.`adultQty`, 
 g.price_kid
 c.`childQty`, 
 g.`group_date`, 
 g.`group_time`, 
 c.`orderStatus`
 FROM `ord_cart` AS c
 JOIN `act_group` AS g ON c.`rel_sid` = g.`act_sid`
 JOIN `act_info` AS i ON i.`act_sid` = g.`act_sid` and c.`rel_seqNum_sid` = g.`group_sid`
 WHERE c.`relType` = 'EVENT' AND c.member_sid = 'mem00001'
 ) AS t
 ORDER BY `relType` desc;
 */
-- 新增主要資料
INSERT INTO
    `ord_order` (
        `order_sid`,
        `member_sid`,
        `coupon_sid`,
        `postAddress`,
        `postType`,
        `postStatus`,
        `treadType`,
        `relAmount`,
        `postAmount`,
        `couponAmount`,
        `order_status`,
        `creator`,
        `createDt`,
        `moder`,
        `modDt`
    )
VALUES
    (
        'ord00001',
        'mem00001',
        NULL,
        '123 Main St',
        1,
        0,
        1,
        1550,
        100,
        NULL,
        0,
        'ChatGPT',
        CURRENT_TIMESTAMP,
        NULL,
        NULL
    );

-- 新增商品明細
INSERT INTO
    `ord_details` (
        `ord_seq_sid`,
        `order_sid`,
        `relType`,
        `rel_sid`,
        `rel_seq_sid`,
        `relName`,
        `rel_seqName`,
        `prodAmount`,
        `prodQty`,
        `adultAmount`,
        `adultQty`,
        `childAmount`,
        `childQty`,
        `amount`
    )
VALUES
    (
        1,
        'ord00001',
        'PROD',
        'P002',
        'PD004',
        'Cat Food B',
        'Cat Food B Large',
        450,
        1,
        NULL,
        NULL,
        NULL,
        NULL,
        450
    ),
    (
        2,
        'ord00001',
        'PROD',
        'P003',
        'PD007',
        'Pet Health C',
        'Pet Health C Large',
        600,
        1,
        NULL,
        NULL,
        NULL,
        NULL,
        600
    ),
    (
        3,
        'ord00001',
        'EVENT',
        '1',
        '4',
        '台北與毛家庭有約，邀你一起來挺寵！',
        '本活動規劃了一系列的項目，包含全台首創、專業五大分科的毛孩免費健診、由獸醫師親自授課的飼主講座，以及毛爸媽最喜歡的毛孩市集！',
        NULL,
        NULL,
        200,
        1,
        100,
        1,
        300
    ),
    (
        4,
        'ord00001',
        'EVENT',
        '1',
        '2',
        '台北與毛家庭有約，邀你一起來挺寵！',
        '本活動規劃了一系列的項目，包含全台首創、專業五大分科的毛孩免費健診、由獸醫師親自授課的飼主講座，以及毛爸媽最喜歡的毛孩市集！',
        NULL,
        NULL,
        200,
        1,
        100,
        1,
        300
    );

-- 查詢訂單資料
/*
 SELECT 
 ord_order.order_sid,
 ord_order.member_sid,
 ord_order.relAmount,
 ord_order.order_status,
 ord_order.createDt,
 ord_details.relType,
 ord_details.rel_sid,
 ord_details.rel_seq_sid,
 ord_details.relName,
 ord_details.rel_seqName,
 ord_details.prodAmount,
 ord_details.prodQty,
 ord_details.adultAmount,
 ord_details.adultQty,
 ord_details.childAmount,
 ord_details.childQty,
 ord_details.amount
 FROM 
 ord_order
 JOIN 
 ord_details
 ON 
 ord_order.order_sid = ord_details.order_sid
 WHERE ord_order.member_sid = 'mem00001';
 */
-- 產生優惠券
INSERT INTO
    mem_coupon_type (
        coupon_sid,
        coupon_code,
        coupon_name,
        coupon_price,
        coupon_startDate,
        coupon_expDate
    )
VALUES
    (
        'coupon001',
        'PROD001',
        '商品折價券1',
        100,
        '2023-05-14',
        '2023-06-14'
    ),
    (
        'coupon002',
        'PROD002',
        '商品折價券2',
        200,
        '2023-05-14',
        '2023-06-14'
    ),
    (
        'coupon003',
        'PROD003',
        '商品折價券3',
        300,
        '2023-05-14',
        '2023-06-14'
    ),
    (
        'coupon004',
        'EVENT001',
        '活動折價券1',
        50,
        '2023-05-14',
        '2023-06-14'
    ),
    (
        'coupon005',
        'EVENT002',
        '活動折價券2',
        100,
        '2023-05-14',
        '2023-06-14'
    ),
    (
        'coupon006',
        'EVENT003',
        '活動折價券3',
        150,
        '2023-05-14',
        '2023-06-14'
    );

INSERT INTO
    mem_coupon_send (
        couponSend_sid,
        coupon_sid,
        member_sid,
        coupon_status
    )
VALUES
    (1, 'coupon001', 'mem00001', 0),
    (2, 'coupon002', 'mem00001', 0),
    (3, 'coupon003', 'mem00001', 0),
    (4, 'coupon004', 'mem00001', 0),
    (5, 'coupon005', 'mem00001', 0),
    (6, 'coupon006', 'mem00001', 0);