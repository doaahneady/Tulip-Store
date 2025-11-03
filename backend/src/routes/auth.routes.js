'use strict';

const { Router } = require('express');
const { body } = require('express-validator');
const { register, login } = require('../controllers/auth.controller');

const router = Router();

router.post(
  '/register',
  [
    body('name').isString().trim().isLength({ min: 2, max: 100 }),
    body('email').isEmail().normalizeEmail(),
    body('password').isString().isLength({ min: 6 }),
  ],
  async (req, res, next) => {
    try {
      await register(req, res);
    } catch (err) {
      next(err);
    }
  }
);

router.post(
  '/login',
  [body('email').isEmail().normalizeEmail(), body('password').isString().notEmpty()],
  async (req, res, next) => {
    try {
      await login(req, res);
    } catch (err) {
      next(err);
    }
  }
);

module.exports = router;


