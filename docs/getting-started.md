---
title: Getting Started

---

# Getting Started

[[toc]]

## Overview

Kasir is a [Laravel](https://laravel.com) package that facilitates integrations with the popular
[Midtrans](https://midtrans.com) payment gateway. With Kasir, you can easily implement a wide range of payment
methods supported by Midtrans, configure request payloads, and handle requests and responses efficiently.

One of the standout features of Kasir is its seamless integration with Laravel config files, which simplifies setup and
enables easy maintenance. If you're looking for a hassle-free way to integrate Midtrans into your Laravel project, Kasir
may be just what you need.

## Laravel Support

Kasir is built to work with Laravel 9.x to Laravel 10.x. If you're using an older version of Laravel, you may need to
upgrade your application to use Kasir.

## MidtransResponse Class

The `MidtransResponse` class that extends Laravel `Response` is a wrapper for the response returned by Midtrans. It
provides a number of useful methods that make it easy to work with the response. For example, you can use the
`successful()` method to check if the transaction was successful. For more information about Laravel `Response`, see
[the Laravel official documentation](https://laravel.com/docs/10.x/responses).
