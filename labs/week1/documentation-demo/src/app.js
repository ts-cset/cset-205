const express = require('express')
const app = express()

/**
 * Express Logger Middleware.
 * 
 * @param {object} req - The Express request object.
 * @param {object} res - The Express response object.
 * @param {function} next - The next function to execute.
 * @see {@link https://expressjs.com/en/guide/writing-middleware.html|Express Middleware}
 */
const myLogger = function (req, res, next) {
  console.log('API received a request')
  next()
}

app.use(myLogger)

app.get('/', (req, res) => {
  res.status(200).send('Hello World!')
})

const blog = require('./blog')
app.use('/blog', blog)

module.exports = app
