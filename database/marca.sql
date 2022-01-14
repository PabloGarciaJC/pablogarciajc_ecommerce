   
SELECT count(p.categoria_id) as 'registros_totales_marca_producto' FROM productos p 
INNER JOIN categorias c ON p.categoria_id = c.id where marca = 'Osiris';

SELECT * FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id where c.id = 10;

SELECT c.id, c.categorias as nombreCategoria, p.nombre, p.marca, p.stock, p.precio, p.oferta, p.imagen from productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE (p.nombre LIKE '%%' OR p.marca LIKE '%%' OR p.stock LIKE '%%' OR p.precio LIKE '%%' OR p.oferta LIKE '%%' OR c.categorias LIKE '%%' );

SELECT p.id, p.categoria_id, c.categorias as nombreCategoria, p.nombre, p.marca, p.stock, p.precio, p.oferta, p.imagen from productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE (p.nombre LIKE '%Telefono1%' OR p.marca LIKE '%Telefono1%' OR p.stock LIKE '%Telefono1%' OR p.precio LIKE '%Telefono1%' OR p.oferta LIKE '%Telefono1%' OR c.categorias LIKE '%Telefono1%') AND p.categoria_id = 13;

SELECT * FROM productos p;

SELECT count(p.categoria_id) as 'registros_totaless' FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id where c.id = 13 and marca = 13;

SELECT p.id, p.nombre, p.marca, p.stock, p.precio, p.oferta, c.categorias from productos p
INNER JOIN categorias c ON p.categoria_id = c.id WHERE (p.nombre LIKE '%raizer%' OR p.marca LIKE '%raizer%' OR p.stock LIKE '%raizer%' OR p.precio LIKE '%raizer%' OR p.oferta LIKE '%raizer%' OR c.categorias LIKE '%raizer%');

SELECT p.marca, COUNT(p.marca) as cuantos from productos p INNER JOIN categorias c ON p.categoria_id = c.id GROUP BY p.marca HAVING COUNT(p.marca) > 1;

SELECT * FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id where (p.precio >= 20 and p.precio <= 50) and c.id = 13;


SELECT precio, marca, memoria_ram FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id and c.id = 13;

SELECT marca, memoria_ram, precio from productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE (p.precio >= 0 and p.precio <= 20 or p.precio >= 50 and p.precio <= 100 or p.precio >= 100 and p.precio <= 1000000) and c.id = 13;


        

SELECT p.id, p.categoria_id, c.categorias as nombreCategoria, p.nombre, p.marca, p.stock, p.precio, p.oferta, p.memoria_ram, p.imagen from productos p INNER JOIN categorias c ON p.categoria_id = c.id and c.id = 13;


SELECT  p.categoria_id, p.marca, p.memoria_ram, precio from productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE (p.nombre LIKE '%2 gb%' OR p.marca LIKE '%2 gb%' OR p.stock LIKE '%2 gb%' OR p.precio LIKE '%2 gb%' OR p.oferta LIKE '%2 gb%' OR c.categorias LIKE '%2 gb%') and p.categoria_id  = 13;



SELECT * from productos p INNER JOIN categorias c ON p.categoria_id = c.id and c.id = 13;

SELECT p.id, p.categoria_id, c.categorias as nombreCategoria, p.nombre, p.marca, p.stock, p.precio, p.oferta, p.memoria_ram, p.imagen from productos p INNER JOIN categorias c ON p.categoria_id = c.id;

SELECT p.id, p.imagen, p.nombre, p.marca, p.stock, p.precio, p.oferta, p.categoria_id, c.categorias as nombreCategoria from productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE p.categoria_id  = 13 ORDER BY p.id DESC LIMIT 0,3";



