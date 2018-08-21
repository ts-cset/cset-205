const express = require('express')
const app = express()
var bodyParser = require("body-parser");

//Here we are configuring express to use body-parser as middle-ware.
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

const cars = [
  {
    id: 0,
    make: "Honda",
    model: "Civic"
  },
  {
    id: 1,
    make: "GMC",
    model: "Sierra"
  },
  {
    id: 2,
    make: "Chevrolet",
    model: "Corvette"
  },
  {
    id: 3,
    make: "Ford",
    model: "Focus"
  },
]

app.get('/', (req, res) => res.send('Hello World!'))

app.get('/cars', (req, res) => res.send(cars))
app.get('/cars/:id', (req, res) => {
  var id = req.params['id']
  for(var i = 0; i < cars.length; i++){
    if(cars[i].id === parseInt(id)){
      res.send(cars[i])
    }
  }
})
app.post('/cars', (req, res) => {
  const id = cars.length
  cars[id] = {
    id: id+1,
    make: req.body.make,
    model: req.body.model
  }
  res.send(cars[id])
})
app.put('/cars/:id', (req, res) => {
  var id = req.params['id']
  for (var property in req.body) {
    cars[id][property] = req.body[property]
  }
  res.send(cars[id])
})

app.delete('/cars/:id', (req, res) => {
  console.log('delete it')
  var id = req.params['id']

  cars.splice(id, 1)
  console.log(cars)
  res.send("ok")
})




app.listen(3000, () => console.log('Cars API listening on port 3000!'))
