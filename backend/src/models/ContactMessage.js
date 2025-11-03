'use strict';

const mongoose = require('mongoose');

const ContactMessageSchema = new mongoose.Schema(
  {
    name: { type: String, required: true, trim: true, minlength: 2, maxlength: 200 },
    email: { type: String, required: true, trim: true, lowercase: true, match: /[^@\s]+@[^@\s]+\.[^@\s]+/ },
    subject: { type: String, required: true, trim: true, minlength: 2, maxlength: 200 },
    message: { type: String, required: true, trim: true, minlength: 2, maxlength: 5000 },
    ip: { type: String },
    userAgent: { type: String },
  },
  { timestamps: true }
);

ContactMessageSchema.index({ createdAt: -1 });

module.exports = mongoose.model('ContactMessage', ContactMessageSchema);


