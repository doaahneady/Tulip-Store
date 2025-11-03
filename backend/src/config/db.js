'use strict';

const mongoose = require('mongoose');

function getMongoUri() {
  const fromEnv = process.env.MONGO_URI;
  if (fromEnv && fromEnv.trim().length > 0) return fromEnv;
  const host = process.env.MONGO_HOST || '127.0.0.1';
  const port = process.env.MONGO_PORT || '27017';
  const dbName = process.env.MONGO_DB || 'Tulip';
  return `mongodb://${host}:${port}/${dbName}`;
}

async function connectToDatabase() {
  const mongoUri = getMongoUri();
  const options = {
    autoIndex: true,
  };

  await mongoose.connect(mongoUri, options);
  mongoose.connection.on('connected', () => {
    console.log('MongoDB connected');
  });
  mongoose.connection.on('error', (err) => {
    console.error('MongoDB connection error:', err);
  });
}

module.exports = { connectToDatabase };


