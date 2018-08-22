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
var lookupCar = (id) => {
  return cars.find(car => {
    return car.id == id
  })
}
var lookupIndex = (id) => {
  return cars.findIndex(car => {
    return car.id == id
  })
}
app.get('/', (req, res) => res.send('Hello World!'))

app.get('/cars', (req, res) => res.send(cars))
app.get('/cars/:id', (req, res) => {
  var id = req.params['id']
  var car = lookupCar(id)
  res.send(car)
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
  var car = lookupCar(id)
  var index = lookupIndex(id)
  for (var property in req.body) {
    car[property] = req.body[property]
  }
  cars[index] = car
  res.send(cars[index])
})

app.delete('/cars/:id', (req, res) => {
  var id = req.params['id']
  var index = lookupIndex(id)
  cars.splice(index, 1)
  res.send("ok")
})




app.listen(3000, () => console.log('Cars API listening on port 3000!'))
