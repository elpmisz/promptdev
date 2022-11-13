<?php

namespace app\classes;

class Query
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function sale_card()
  {
    $sql = "SELECT FORMAT(SUM(subtotal),2) as total,
    FORMAT(SUM(CASE WHEN DATE(B.date) = DATE(NOW()) THEN subtotal ELSE 0 END),2) today,
    FORMAT(SUM(CASE WHEN MONTH(B.date) = MONTH(NOW()) THEN subtotal ELSE 0 END),2) month,
    FORMAT(SUM(CASE WHEN YEAR(B.date) = YEAR(NOW()) THEN subtotal ELSE 0 END),2) year
    FROM sma_sale_items A 
    LEFT JOIN sma_sales B 
    ON A.sale_id = B.id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetch();
  }

  public function product_all_top()
  {
    $sql = "SELECT product_name,
    FORMAT(SUM(quantity),0) as amount,
    FORMAT(SUM(subtotal),2) as total 
    FROM sma_sale_items 
    GROUP BY product_code
    ORDER BY SUM(subtotal) DESC 
    LIMIT 10";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function product_year_top()
  {
    $sql = "SELECT product_name,
    FORMAT(SUM(quantity),0) as amount,
    FORMAT(SUM(subtotal),2) as total 
    FROM sma_sale_items A 
    LEFT JOIN sma_sales B
    ON A.sale_id = B.id
    WHERE YEAR(date) = YEAR(NOW())
    GROUP BY product_code
    ORDER BY SUM(subtotal) DESC 
    LIMIT 10";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function product_month_top()
  {
    $sql = "SELECT product_name,
    FORMAT(SUM(quantity),0) as amount,
    FORMAT(SUM(subtotal),2) as total 
    FROM sma_sale_items A 
    LEFT JOIN sma_sales B
    ON A.sale_id = B.id
    WHERE YEAR(date) = YEAR(NOW())
    AND MONTH(date) = MONTH(NOW())
    GROUP BY product_code
    ORDER BY SUM(subtotal) DESC 
    LIMIT 10";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function category_all_top()
  {
    $sql = "SELECT C.name,
    FORMAT(SUM(A.quantity),0) as amount,
    FORMAT(SUM(subtotal),2) as total 
    FROM sma_sale_items A 
    LEFT JOIN sma_products B
    ON A.product_id = B.id
    LEFT JOIN sma_categories C 
    ON B.category_id = C.id
    GROUP BY C.name
    ORDER BY SUM(subtotal) DESC
    LIMIT 10";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function category_year_top()
  {
    $sql = "SELECT C.name,
    FORMAT(SUM(A.quantity),0) as amount,
    FORMAT(SUM(subtotal),2) as total 
    FROM sma_sale_items A 
    LEFT JOIN sma_products B
    ON A.product_id = B.id
    LEFT JOIN sma_categories C 
    ON B.category_id = C.id
    LEFT JOIN sma_sales D 
    ON A.sale_id = D.id
    WHERE YEAR(date) = YEAR(NOW())
    GROUP BY C.name
    ORDER BY SUM(subtotal) DESC
    LIMIT 10;";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function category_month_top()
  {
    $sql = "SELECT C.name,
    FORMAT(SUM(A.quantity),0) as amount,
    FORMAT(SUM(subtotal),2) as total 
    FROM sma_sale_items A 
    LEFT JOIN sma_products B
    ON A.product_id = B.id
    LEFT JOIN sma_categories C 
    ON B.category_id = C.id
    LEFT JOIN sma_sales D 
    ON A.sale_id = D.id
    WHERE YEAR(date) = YEAR(NOW())
    AND MONTH(date) = MONTH(NOW())
    GROUP BY C.name
    ORDER BY SUM(subtotal) DESC
    LIMIT 10;";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function product_count()
  {
    $sql = "SELECT name,
    FORMAT(price,2) price,FORMAT(quantity,0) qty,
    FORMAT(cost * quantity,2) cost,
    FORMAT((price * quantity) - ((price * quantity) * tax_rate / 100),2) sale
    FROM sma_products 
    ORDER BY quantity ASC 
    LIMIT 10";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function category_count()
  {
    $sql = "SELECT B.name, SUM(CASE WHEN A.id THEN 1 ELSE 0 END) count,
    FORMAT(SUM(cost * quantity),2) cost,
    FORMAT(SUM(price * quantity) - (SUM(price * quantity) * tax_rate / 100),2) sale
    FROM sma_products A 
    LEFT JOIN sma_categories B 
    ON A.category_id = B.id
    GROUP BY A.category_id
    ORDER BY SUM(CASE WHEN A.id THEN 1 ELSE 0 END) DESC";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function category_select($keyword)
  {
    $sql = "SELECT A.category_id id,B.name
    FROM sma_products A 
    LEFT JOIN sma_categories B
    ON A.category_id = B.id ";
    if ($keyword) {
      $sql .= " WHERE (B.code LIKE '%{$keyword}%' OR B.name LIKE '%{$keyword}%')";
    }
    $sql .= " GROUP BY A.category_id ";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function month_th($month)
  {
    $months = [
      "",
      "มกราคม",
      "กุมภาพันธ์",
      "มีนาคม",
      "เมษายน",
      "พฤษภาคม",
      "มิถุนายน",
      "กรกฎาคม",
      "สิงหาคม",
      "กันยายน",
      "ตุลาคม",
      "พฤศจิกายน",
      "ธันวาคม"
    ];
    return $months[$month];
  }
}