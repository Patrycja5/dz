
* {
  font-family: 'Inter', sans-serif;
}


body {
  margin: 0;
  background: #fff5e1;
  color: #000;

}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 30px 60px;
}

.logo img
{
width: 250px;
  height: 250px;

}

.logo  {
 width: 200px;
  height: 200px;
  background-color: #ffdb8b;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 55px auto 20px auto;
}
  
.user-panel {
  position: absolute;
  top: 10px;
  right: 10px;
  display: flex;
  gap: 15px;
  align-items: center;
}

.user-links a {
  font-size: 14px;
  text-decoration: none;
  color: #333;
  margin-left: 10px;
}

.user-links a:hover {
  text-decoration: underline;
}

nav {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin: 40px 0;
  flex-wrap: wrap;
}

nav button {
  padding: 10px 20px;
  border: none;
  border-radius: 20px;
  background-color: #ffd983;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s ease; 
}

nav button:hover {
  transform: translateY(-2px) scale(1.05); 
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

nav .add-report .plus {
  font-size: 20px;             
  display: inline-block;       
  transition: transform 0.4s ease, color 0.3s ease;
  margin-right: 8px;
}

nav .add-report:hover .plus {
  transform: rotate(180deg);            
}

nav .add-report {
  background-color: #ffd983;
  color: black;
  
}

nav .urgent {
  background-color: #e57373;
  color: black;
}

nav .urgent.active {
  border-bottom: 5px solid red;
}


.button:hover {
  background-color: #f4c146;
  transform: scale(1.05) translateY(-2px); 
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); 
}


.gallery, .about {
  background-color: #ffd983;
  width: 80%;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
}

h1{
  text-align: center;
}

.gł{
  border: 1px solid black;
  border-radius: 4px;
  padding: 2px 6px;
}

.in{
  border: 1px solid black;
  border-radius: 4px;
  padding: 2px 6px;
}


.gallery {
  width: 600px;
  margin: 20px auto;
}

.karuzel {
  position: relative;
  width: 100%;
  height: 250px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #eee;
  border-radius: 8px;
}

.karuzel img {
  max-width: 100%;
  max-height: 100%;
  border-radius: 8px;
  user-select: none;
}

.karuzel button {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(50, 50, 50, 0.5);
  border: none;
  color: white;
  font-size: 24px;
  width: 40px;
  height: 40px;
  cursor: pointer;
  border-radius: 50%;
}

.karuzel .prev {
  left: 10px;
}

.karuzel .next {
  right: 10px;
}

.site-footer {
  background-color: #222;
  color: #eee;
  text-align: center;
  padding: 20px 10px;
  font-size: 14px;
  margin-top: 40px;
}

.site-footer a {
  color: #ffdb8b;
  text-decoration: none;
  margin: 0 5px;
}

.site-footer a:hover {
  text-decoration: underline;
}




body {
  font-family: 'Inter', sans-serif;
  background-color: #f9f9f9;
  color: #333;
  margin: 0;
  padding: 20px;
}

h2 {
  text-align: center;
  color: #222;
  margin-bottom: 30px;
}

form {
  background-color: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  margin: 0 auto 40px;
}

form input[type="text"],
form textarea {
  width: 100%;
  padding: 10px;
  margin-top: 6px;
  margin-bottom: 16px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 16px;
}

form button {
  background-color: #0077cc;
  color: white;
  padding: 10px 18px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
}

form button:hover {
  background-color: #005fa3;
}

.ogloszenie {
  background-color: #fff;
  padding: 15px 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  margin-bottom: 20px;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.ogloszenie h3 {
  margin: 0;
  font-size: 18px;
  color: #0077cc;
}

.ogloszenie p {
  margin: 10px 0;
}

.ogloszenie a {
  color: #cc0000;
  text-decoration: none;
  font-size: 14px;
}

.ogloszenie a:hover {
  text-decoration: underline;
}




