--R001購物車--用name/mobile 撈member資料
SELECT
    member_sid,
    member_name,
    member_birth
FROM
    mem_member mm
WHERE
    member_name = XXX / member_mobile = XXX --C001購物車--某MEMBER的全部SHOP商品
SELECT
FROM
    ord_cart oc
    JOIN shop_pro sp ON oc.rel_sid = sp.prod_sid
    JOIN shop_proDet spd ON sp.prod_sid = spd.prod_sid
WHERE
    oc.member_sid = 1;

--C002購物車--某MEMBER的全部EVENT活動
SELECT
FROM
    ord_cart oc
    JOIN act_info ai ON oc.rel_sid = ai.act_sid
    JOIN act_group ag ON ai.act_sid = ag.act_sid
WHERE
    oc.member_sid = 1;