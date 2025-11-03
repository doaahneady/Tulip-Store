'use strict';

const { Router } = require('express');
const { body } = require('express-validator');
const { submitContact } = require('../controllers/contact.controller');

const router = Router();

router.post(
  '/',
  [
    body('name').isString().trim().isLength({ min: 2, max: 200 }),
    body('email').isEmail().normalizeEmail(),
    body('subject').isString().trim().isLength({ min: 2, max: 200 }),
    body('message').isString().trim().isLength({ min: 2, max: 5000 }),
  ],
  async (req, res, next) => {
    try {
      await submitContact(req, res);
    } catch (err) {
      next(err);
    }
  }
);

module.exports = router;


