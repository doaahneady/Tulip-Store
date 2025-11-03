'use strict';

const express = require('express');
const cors = require('cors');
const morgan = require('morgan');
const dotenv = require('dotenv');
const { connectToDatabase } = require('./config/db');

dotenv.config();

const app = express();

// Middlewares
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(morgan('dev'));

// Health check
app.get('/health', (_req, res) => {
  res.json({ status: 'ok', service: 'tulip-backend' });
});

// Routes
app.use('/api/auth', require('./routes/auth.routes'));
app.use('/api/contact', require('./routes/contact.routes'));

// 404 handler
app.use((_req, res) => {
  res.status(404).json({ message: 'Not Found' });
});

// Start server after DB connection
const PORT = process.env.PORT || 4000;

connectToDatabase()
  .then(() => {
    app.listen(PORT, () => {
      console.log(`Tulip backend listening on port ${PORT}`);
    });
  })
  .catch((error) => {
    console.error('Failed to start server due to DB error:', error);
    process.exit(1);
  });


