SELECT * FROM productos p;   

SELECT p.id, p.categoria_id, c.categorias as nombreCategoria, p.nombre, p.marca, p.stock, p.precio, p.oferta, p.imagen from productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE (p.nombre LIKE '%Telefono1%' OR p.marca LIKE '%Telefono1%' OR p.stock LIKE '%Telefono1%' OR p.precio LIKE '%Telefono1%' OR p.oferta LIKE '%Telefono1%' OR c.categorias LIKE '%Telefono1%') AND p.categoria_id = 13;

SELECT p.marca, COUNT(p.marca) as cuantos from productos p INNER JOIN categorias c ON p.categoria_id = c.id GROUP BY p.marca HAVING COUNT(p.marca) > 1;
SELECT marca, memoria_ram, precio from productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE (p.precio >= 0 and p.precio <= 20 or p.precio >= 50 and p.precio <= 100 or p.precio >= 100 and p.precio <= 1000000) and c.id = 13;


SELECT p.id, p.categoria_id, p.marca, p.memoria_ram, precio FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id and c.id = 15 ORDER BY id, memoria_ram ASC;




SELECT DISTINCT p.memoria_ram FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id and c.id = 15 ORDER BY p.id asc; 




SELECT p.categoria_id, p.marca, p.memoria_ram, precio FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id where (p.marca = 'Apple') ;

SELECT p.memoria_ram from productos p INNER JOIN categorias c ON p.categoria_id = c.id and c.id = 15;


SELECT p.marca, p.memoria_ram from productos p INNER JOIN categorias c ON p.categoria_id = c.id and c.id = 15 GROUP BY p.memoria_ram ORDER BY memoria_ram + 0 asc;

SELECT p.id, p.marca, p.memoria_ram from productos p ORDER BY p.id asc;

SELECT * from productos p INNER JOIN categorias c ON p.categoria_id = c.id and c.id = 15 GROUP BY p.memoria_ram ORDER BY p.memoria_ram DESC;

SELECT COUNT(precio) FROM PRODUCTOS GROUP BY precio ORDER BY precio + 0 ASC;

SELECT * FROM PRODUCTOS;


SELECT p.id, p.imagen, p.nombre, p.marca, p.stock, p.precio, p.oferta, p.categoria_id, c.categorias as nombreCategoria from productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE categoria_id = 15
