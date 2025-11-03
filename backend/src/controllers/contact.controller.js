'use strict';

const { validationResult } = require('express-validator');
const ContactMessage = require('../models/ContactMessage');

async function submitContact(req, res) {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ errors: errors.array() });
  }

  const { name, email, subject, message } = req.body;
  const ip = req.headers['x-forwarded-for']?.split(',')[0]?.trim() || req.socket?.remoteAddress || req.ip;
  const userAgent = req.headers['user-agent'];

  const doc = await ContactMessage.create({ name, email, subject, message, ip, userAgent });

  return res.status(201).json({ id: doc._id, createdAt: doc.createdAt });
}

module.exports = { submitContact };


