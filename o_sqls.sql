--R001購物車--用name/mobile 撈member資料
SELECT
    `member_sid`,
    `member_name`,
    `member_mobile`,
    `member_birth`
FROM
    `mem_member`
WHERE
    `member_sid` = xxx;

SELECT
    `member_sid`,
    `member_name`,
    `member_mobile`,
    `member_birth`
FROM
    `mem_member`
WHERE
    `member_mobile` = xxx;

SELECT
    `member_sid`,
    `member_name`,
    `member_mobile`,
    `member_birth`
FROM
    `mem_member`
WHERE
    `member_name` = xxx;

--R002某member沒有過期的coupons
SELECT
    cs.member_sid,
    cs.coupon_sid,
    ct.coupon_code,
    ct.coupon_name,
    ct.coupon_price,
    ct.coupon_expDate
FROM
    mem_coupon_send cs
    JOIN mem_coupon_type ct ON cs.coupon_sid = ct.coupon_sid
WHERE
    ct.coupon_expDate > NOW()
    AND cs.coupon_status = 0
    AND cs.member_sid = 'mem00001';

--R003 某會員購物車裡的商城商品
SELECT
    sp.`pro_sid`,
    sp.`pro_name`,
    spd.`proDet_sid`,
    spd.`proDet_name`,
    spd.`proDet_price`,
    spd.`proDet_qty`
FROM
    `ord_cart` oc
    JOIN `shop_pro` sp ON oc.`rel_sid` = sp.`pro_sid`
    JOIN `shop_prodet` spd ON sp.`pro_sid` = spd.`pro_sid`
    AND oc.`rel_seqNum_sid` = spd.`proDet_sid`
WHERE
    oc.member_sid = 'mem00001';

--R002購物車--某MEMBER的全部EVENT活動
SELECT
    ai.`act_sid`,
    ai.`act_name`,
    ag.`group_sid`,
    ag.`group_date`,
    ag.`price_adult`,
    ag.`price_kid`,
    ag.`ppl_max`
FROM
    `ord_cart` oc
    JOIN `act_info` ai ON oc.`rel_sid` = ai.`act_sid`
    JOIN `act_group` ag ON ai.`act_sid` = ag.`act_sid`
    AND oc.`rel_seqNum_sid` = ag.`group_sid`
WHERE
    oc.member_sid = 'mem00001';

-- 更新購物車
UPDATE
    `ord_cart`
SET
    `orderStatus` = '002'
WHERE
    `rel_sid` = 'P001'
    and `rel_seqNum_sid` = 'PD003';