'use strict';

const mongoose = require('mongoose');

const LoginLogSchema = new mongoose.Schema(
  {
    userId: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: false },
    emailAttempted: { type: String, required: true, lowercase: true, trim: true },
    success: { type: Boolean, required: true },
    ip: { type: String },
    userAgent: { type: String },
  },
  { timestamps: true }
);

LoginLogSchema.index({ createdAt: -1 });
LoginLogSchema.index({ emailAttempted: 1, createdAt: -1 });

module.exports = mongoose.model('LoginLog', LoginLogSchema);


