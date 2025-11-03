'use strict';

const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const { validationResult } = require('express-validator');
const User = require('../models/User');
const LoginLog = require('../models/LoginLog');

function signToken(userId) {
  const secret = process.env.JWT_SECRET || 'dev_insecure_secret_change_me';
  const expiresIn = process.env.JWT_EXPIRES_IN || '7d';
  return jwt.sign({ sub: userId }, secret, { expiresIn });
}

async function register(req, res) {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ errors: errors.array() });
  }

  const { name, email, password } = req.body;

  const existing = await User.findOne({ email });
  if (existing) {
    return res.status(409).json({ message: 'Email already registered' });
  }

  const salt = await bcrypt.genSalt(12);
  const passwordHash = await bcrypt.hash(password, salt);

  const user = await User.create({ name, email, passwordHash });
  const token = signToken(user._id.toString());

  return res.status(201).json({
    token,
    user: {
      id: user._id,
      name: user.name,
      email: user.email,
      createdAt: user.createdAt,
    },
  });
}

async function login(req, res) {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ errors: errors.array() });
  }

  const { email, password } = req.body;
  const user = await User.findOne({ email });
  const ip = req.headers['x-forwarded-for']?.split(',')[0]?.trim() || req.socket?.remoteAddress || req.ip;
  const userAgent = req.headers['user-agent'];

  if (!user) {
    await LoginLog.create({ userId: undefined, emailAttempted: email, success: false, ip, userAgent });
    return res.status(401).json({ message: 'Invalid credentials' });
  }

  const isMatch = await bcrypt.compare(password, user.passwordHash);
  if (!isMatch) {
    await LoginLog.create({ userId: user._id, emailAttempted: email, success: false, ip, userAgent });
    return res.status(401).json({ message: 'Invalid credentials' });
  }

  await LoginLog.create({ userId: user._id, emailAttempted: email, success: true, ip, userAgent });

  const token = signToken(user._id.toString());
  return res.json({
    token,
    user: {
      id: user._id,
      name: user.name,
      email: user.email,
      createdAt: user.createdAt,
    },
  });
}

module.exports = { register, login };


