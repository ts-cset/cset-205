/**
 * A module that creates and exports the Blog routes.
 * @module router/blog
 */

const express = require('express')
const router = express.Router()


/**
 * Respond to GET request on the blog route
 * @memberof router/blog
 * @requires express/Router
 * @param {object} req - The Express request object.
 * @param {object} res - The Express response object.
 * @see {@link https://expressjs.com/en/starter/basic-routing.html|Express Routing}
 * @swagger
 * /blog:
 *   get:
 *     description: Get /blog
 *     produces:
 *       - text/html
 *     responses:
 *       200:
 *         description: blog:get
 */
router.get('/', function (req, res) {
  res.status(200).send('Hello World!')
})

/**
 * POST request on the blog route to create a new resource
 * @memberof router/blog
 * @param {object} req - The Express request object.
 * @param {object} res - The Express response object.
 * @see {@link https://expressjs.com/en/starter/basic-routing.html|Express Routing}
 * @swagger
 * /blog:
 *   post:
 *     description: Post /blog
 *     produces:
 *       - text/html
 *     responses:
 *       201:
 *         description: blog:post
 */
router.post('/', (req, res) => {
  res.status(201).send('The server received a POST request')
})

module.exports = router
