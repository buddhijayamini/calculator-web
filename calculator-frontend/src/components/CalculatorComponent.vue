<template>
    <div class="calculator">
      <div class="display">{{ current }}</div>
      <div class="buttons">
        <button @click="clear">AC</button>
        <button @click="sign">+/-</button>
        <button @click="percent">%</button>
        <button @click="setOperator('/')">÷</button>
        <button @click="append(7)">7</button>
        <button @click="append(8)">8</button>
        <button @click="append(9)">9</button>
        <button @click="setOperator('*')">×</button>
        <button @click="append(4)">4</button>
        <button @click="append(5)">5</button>
        <button @click="append(6)">6</button>
        <button @click="setOperator('-')">−</button>
        <button @click="append(1)">1</button>
        <button @click="append(2)">2</button>
        <button @click="append(3)">3</button>
        <button @click="setOperator('+')">+</button>
        <button @click="append(0)" class="zero">0</button>
        <button @click="append('.')">.</button>
        <button @click="calculate">=</button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        current: '',
        operator: null,
        operand1: null,
        waitingForOperand2: false
      };
    },
    methods: {
      clear() {
        this.current = '';
        this.operator = null;
        this.operand1 = null;
        this.waitingForOperand2 = false;
      },
      sign() {
        this.current = this.current.charAt(0) === '-' ? this.current.slice(1) : '-' + this.current;
      },
      percent() {
        this.current = (parseFloat(this.current) / 100).toString();
      },
      append(number) {
        if (this.waitingForOperand2) {
          this.current = number.toString();
          this.waitingForOperand2 = false;
        } else {
          this.current = this.current === '' ? number.toString() : this.current + number.toString();
        }
      },
      setOperator(operator) {
        if (this.operator !== null) {
          this.calculate();
        }
        this.operand1 = this.current;
        this.operator = operator;
        this.waitingForOperand2 = true;
      },
      async calculate() {
        try {
          const operand2 = this.current;
          const response = await axios.post('http://localhost:8000/api/calculate', {
            operand1: parseFloat(this.operand1),
            operand2: parseFloat(operand2),
            operation: this.operator
          });
  
          this.current = response.data.calculation.result.toString();
          this.operator = null;
          this.operand1 = null;
          this.waitingForOperand2 = false;
        } catch (error) {
          console.error('Calculation error:', error.response ? error.response.data : error.message);
        }
      }
    }
  };
  </script>
  
  <style>
  .calculator {
    width: 320px;
    height: 520px;
    background-color: #000;
    border-radius: 20px;
    box-shadow: 0px 0px 20px #000;
    padding: 20px;
  }
  
  .display {
    color: white;
    font-size: 48px;
    text-align: right;
    padding: 10px;
    background-color: black;
    border-radius: 10px;
    margin-bottom: 20px;
  }
  
  .buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 10px;
  }
  
  button {
    font-size: 24px;
    padding: 20px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
  }
  
  button:nth-child(4n) {
    background-color: orange;
    color: white;
  }
  
  button:nth-child(-n+3) {
    background-color: #a5a5a5;
    color: black;
  }
  
  button:nth-child(n+5):not(:nth-child(4n)) {
    background-color: #333;
    color: white;
  }
  
  button.zero {
    grid-column: span 2;
  }
  </style>
  