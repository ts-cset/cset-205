const request = require('supertest') // this is a test helper
const app = require('../src/app')

describe('Test the /blog path', () => {
  // GET request to /blog
  test('GET /blog should respond with status 200', () => {
    return request(app).get('/blog').then(response => {
      expect(response.statusCode).toBe(200)
    })
  })

  /*
   * A REST API responds with the 201 status code when a resource is created
   * inside a collection. There may also be times when a new resource is created
   * as a result of some controller action, in which case 201 would also be an
   * appropriate response.
   */
  // POST request to /blog
  test('POST /blog should respond with status 201', () => {
    return request(app).post('/blog').then(response => {
      expect(response.statusCode).toBe(201)
    })
  })
})
