const express = require('express')
const router = express()
var bodyParser = require("body-parser");

//Here we are configuring express to use body-parser as middle-ware.
router.use(bodyParser.urlencoded({ extended: false }));
router.use(bodyParser.json());

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
const version='/v1'
router.get(version+'/', (req, res) => res.send('Hello World!'))

router.get(version+'/cars', (req, res) => res.send(cars))

router.get(version+'/cars/:id', (req, res) => {
  var id = req.params['id']
  if(car){
    var car = lookupCar(id)
    res.send(car)
  } else {
    res.status = 404
    res.send('not found')
  }
})

router.post(version+'/cars', (req, res) => {
  const id = cars.length
  cars[id] = {
    id: id+1,
    make: req.body.make,
    model: req.body.model
  }
  res.send(cars[id])
})

router.put(version+'/cars/:id', (req, res) => {
  var id = req.params['id']
  var car = lookupCar(id)
  var index = lookupIndex(id)
  for (var property in req.body) {
    car[property] = req.body[property]
  }
  cars[index] = car
  res.send(cars[index])
})

router.delete(version+'/cars/:id', (req, res) => {
  // NOTE: this has a bug.  We will address this bug as part of class.
  var id = req.params['id']
  var index = lookupIndex(id)
  if(index >= 0) {
    cars.splice(index, 1)
    res.send("ok")
  }
  res.status = 404
  res.send('not found')
})




router.listen(3000, () => console.log('Cars API listening on port 3000!'))
