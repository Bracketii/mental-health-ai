# Mental Health AI Coach Platform

A Laravel-based web application that provides personalized mental health support using AI-powered coaches. Built with Laravel Livewire, OpenAI integration, and Retrieval-Augmented Generation (RAG) architecture, the platform offers a chat-based experience with multiple mental health AI coaches tailored to individual users. It includes dynamic pricing, subscription management via Stripe with Laravel Cashier, and user onboarding with questionnaires to match users with the best AI coach.

## Table of Contents

- [Features](#features)
- [Technologies](#technologies)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Deployment](#deployment)
- [Usage](#usage)
- [API Integration](#api-integration)
- [License](#license)

## Features

- **AI Coaches**: Multiple AI-driven mental health coaches, each specialized in different areas of mental health.
- **RAG Architecture**: AI models are enhanced with custom datasets for better contextual answers, powered by Retrieval-Augmented Generation (RAG) with a vector store.
- **Chat-based Interface**: Users can communicate with the AI coaches via a chat interface built using Laravel Livewire.
- **Questionnaire-based Onboarding**: A detailed questionnaire on signup to assess users' mental health needs and assign the most appropriate AI coach.
- **Dynamic Pricing with Stripe**: Integration with Stripe for subscription-based payments using Laravel Cashier.
- **User Dashboard**: Each user can manage their subscriptions, view chat history, and update personal details.
- **Admin Dashboard**: Manage users, AI datasets, subscription plans, and coach configurations.
- **Secure Authentication**: Built-in authentication with Jetstream and Livewire for user management.
- **Deployable on DigitalOcean or any other cloud platform**: Supports one-click deployment via Laravel Forge and Envoyer.

## Technologies

- **Backend**: Laravel (Livewire, Jetstream, Cashier)
- **Frontend**: Tailwind CSS, Alpine.js, Livewire for dynamic UIs
- **AI Models**: OpenAI (GPT-4) with custom-trained datasets
- **Database**: MySQL for user and subscription data, Vector Store for AI response retrieval
- **Payments**: Stripe for subscription management
- **DevOps**: Docker, Laravel Forge, Envoyer for deployment and CI/CD

## Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL or any other supported DB
- Stripe Account for Payment Integration
- OpenAI API Key
- Laravel Forge/Envoyer (optional but recommended for deployment)
