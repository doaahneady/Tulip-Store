import { useEffect, useState } from 'react'

export default function SignIn() {
  const [formState, setFormState] = useState<'signin' | 'signup' | 'forgot' | 'verify' | 'reset'>('signin')
  const [countries, setCountries] = useState<any[]>([])
  const [selectedCountry, setSelectedCountry] = useState<any>(null)

  useEffect(() => {
    const loadCountries = async () => {
      try {
        const res = await fetch('http://localhost:8000/api/countries')
        const data = await res.json()
        const countries = Array.isArray(data) ? data.map((c: any) => ({ name: c.name, iso2: c.iso2, dialCode: c.dial_code })) : []
        setCountries(countries.sort((a, b) => a.name.localeCompare(b.name)))
      } catch (e) {
        setCountries([
          { name: 'United States', iso2: 'us', dialCode: '1' },
          { name: 'United Arab Emirates', iso2: 'ae', dialCode: '971' },
          { name: 'Saudi Arabia', iso2: 'sa', dialCode: '966' },
          { name: 'Syria', iso2: 'sy', dialCode: '963' },
          { name: 'Lebanon', iso2: 'lb', dialCode: '961' },
          { name: 'Egypt', iso2: 'eg', dialCode: '20' },
          { name: 'Jordan', iso2: 'jo', dialCode: '962' }
        ])
      }
    }
    loadCountries()
  }, [])

  return (
    <div style={{ backgroundColor: '#0d464c', minHeight: '100vh', display: 'flex', alignItems: 'center', padding: '20px' }}>
      <style>{`
        @import url(https://db.onlinewebfonts.com/c/ba155473d72fb574bd945b20f4598560?family=Montserrat-Alt1+SemBd);
        @import url(https://db.onlinewebfonts.com/c/29295d2928c6bd76a76c9e789982bc85?family=Montserrat-Alt1+Med);

        @font-face {
          font-family: "Montserrat-Alt1 SemBd";
          src: url("https://db.onlinewebfonts.com/t/ba155473d72fb574bd945b20f4598560.eot");
          src: url("https://db.onlinewebfonts.com/t/ba155473d72fb574bd945b20f4598560.eot?#iefix") format("embedded-opentype"),
            url("https://db.onlinewebfonts.com/t/ba155473d72fb574bd945b20f4598560.woff2") format("woff2"),
            url("https://db.onlinewebfonts.com/t/ba155473d72fb574bd945b20f4598560.woff") format("woff"),
            url("https://db.onlinewebfonts.com/t/ba155473d72fb574bd945b20f4598560.ttf") format("truetype"),
            url("https://db.onlinewebfonts.com/t/ba155473d72fb574bd945b20f4598560.svg#Montserrat-Alt1 SemBd") format("svg");
        }

        @font-face {
          font-family: "Montserrat-Alt";
          src: url("https://db.onlinewebfonts.com/t/29295d2928c6bd76a76c9e789982bc85.eot");
          src: url("https://db.onlinewebfonts.com/t/29295d2928c6bd76a76c9e789982bc85.eot?#iefix") format("embedded-opentype"),
            url("https://db.onlinewebfonts.com/t/29295d2928c6bd76a76c9e789982bc85.woff2") format("woff2"),
            url("https://db.onlinewebfonts.com/t/29295d2928c6bd76a76c9e789982bc85.woff") format("woff"),
            url("https://db.onlinewebfonts.com/t/29295d2928c6bd76a76c9e789982bc85.ttf") format("truetype"),
            url("https://db.onlinewebfonts.com/t/29295d2928c6bd76a76c9e789982bc85.svg#Montserrat-Alt1 Med") format("svg");
        }

        .page-container {
          min-height: 600px;
          padding: 20px;
          width: 500px;
          margin: 0 auto;
        }

        .forget-link, .signup-link {
          background-color: transparent;
          color: #0d464c;
          text-decoration: underline;
          font-weight: bold;
          border: none;
          cursor: pointer;
        }

        .forget-link:hover, .signup-link:hover {
          font-weight: bold;
          text-shadow: 1px 0 2px #000000;
          transition: 0.2s;
        }

        .card-signup {
          border-radius: 40px;
          width: 100%;
          height: auto;
          background-color: #ffffff80;
          backdrop-filter: blur(20px);
          color: #0d464c;
          border: 2px solid transparent;
          box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .card-body {
          padding: 30px;
        }

        h3 {
          color: #0d464c;
          font-weight: 700;
          font-size: 40px;
          text-shadow: 1px 0 2px #000000;
          text-align: center;
          margin-bottom: 30px;
          font-family: "Montserrat-Alt1 SemBd";
        }

        .message-error {
          color: red;
          display: none;
          font-size: 0.85rem;
          margin-top: 4px;
        }

        .btn {
          color: #e7c8b3;
          background-color: #0d464c;
          font-weight: bolder;
          border: none;
          font-size: 16px;
          box-shadow: 2px 0 4px #000000;
          cursor: pointer;
          padding: 12px 20px;
        }

        .btn:hover {
          background-color: #f05928;
          color: #e7c8b3;
        }

        .form-control {
          border-radius: 10px;
          border: 1px solid #e5e9ef;
          padding: 10px 15px;
          margin-bottom: 15px;
          font-family: "Montserrat-Alt1 SemBd";
        }

        .form-control.is-invalid {
          border-color: #dc3545;
          background-color: #fff5f5;
        }

        .form-control.is-valid {
          border-color: #28a745;
        }

        .form-select {
          border-radius: 10px;
          border: 1px solid #e5e9ef;
          font-weight: 500;
          background-color: #ffffff;
          color: #0d464c;
          padding: 10px;
          font-family: "Montserrat-Alt1 SemBd";
        }

        .text {
          color: #0d464c;
          margin: 10px 0;
          font-family: "Montserrat-Alt1 SemBd";
        }

        .tolip {
          background-image: url("/images/tulip.png");
          background-size: cover;
          width: 200px;
          height: 200px;
          margin: 0 auto 15px;
        }
      `}</style>

      <div className="page-container">
        <div className="card-signup">
          <div className="card-body">
            <div className="tolip"></div>
            <h3>
              {formState === 'signin' && 'SIGN IN'}
              {formState === 'signup' && 'SIGN UP'}
              {formState === 'forgot' && 'FORGOT PASSWORD'}
              {formState === 'verify' && 'VERIFY EMAIL'}
              {formState === 'reset' && 'RESET PASSWORD'}
            </h3>

            {formState === 'signin' && (
              <SignInForm onSignUp={() => setFormState('signup')} onForgot={() => setFormState('forgot')} onVerifyNeeded={() => setFormState('verify')} />
            )}

            {formState === 'signup' && (
              <SignUpForm countries={countries} selectedCountry={selectedCountry} onCountrySelect={setSelectedCountry} onBack={() => setFormState('signin')} onVerifyNeeded={() => setFormState('verify')} />
            )}

            {formState === 'forgot' && (
              <ForgotPasswordForm onBack={() => setFormState('signin')} onReset={() => setFormState('reset')} />
            )}

            {formState === 'verify' && (
              <VerifyForm onBack={() => setFormState('signin')} />
            )}

            {formState === 'reset' && (
              <ResetPasswordForm onBack={() => setFormState('signin')} />
            )}
          </div>
        </div>
      </div>
    </div>
  )
}

function SignInForm({ onSignUp, onForgot, onVerifyNeeded }: any) {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [emailErr, setEmailErr] = useState(false)
  const [passErr, setPassErr] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setEmailErr(false)
    setPassErr(false)

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!email || !emailRegex.test(email)) {
      setEmailErr(true)
      return
    }
    if (!password) {
      setPassErr(true)
      return
    }

    try {
      const res = await fetch('http://localhost:8000/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      })
      const data = await res.json()
      if (data.data?.token) {
        localStorage.setItem('token', data.data.token)
        localStorage.setItem('user', JSON.stringify(data.data.user || {}))
        window.location.href = '/'
      } else {
        alert(data.message || 'Login failed')
      }
    } catch (e) {
      alert('Network error')
    }
  }

  return (
    <form onSubmit={handleSubmit} className="row g-3">
      <div className="col-12 mb-3">
        <input type="email" className={`form-control ${emailErr ? 'is-invalid' : ''}`} placeholder="E-mail" value={email} onChange={(e) => setEmail(e.target.value)} />
        {emailErr && <div className="message-error" style={{ display: 'block' }}>Check your email</div>}
      </div>
      <div className="col-12 mb-3">
        <input type="password" className={`form-control ${passErr ? 'is-invalid' : ''}`} placeholder="Password" value={password} onChange={(e) => setPassword(e.target.value)} />
        {passErr && <div className="message-error" style={{ display: 'block' }}>Check your password</div>}
      </div>
      <div className="mb-3">
        <button type="button" className="forget-link" onClick={onForgot}>
          forget password?
        </button>
      </div>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <button type="submit" className="btn btn-lg w-100">
          Sign In
        </button>
      </div>
      <p className="text">
        Don't have an account? <button type="button" className="signup-link" onClick={(e) => { e.preventDefault(); onSignUp(); }}>
          Sign up here
        </button>
      </p>
    </form>
  )
}

function SignUpForm({ countries, selectedCountry, onCountrySelect, onBack, onVerifyNeeded }: any) {
  const [formData, setFormData] = useState({ username: '', name: '', email: '', phone: '', password: '', language: '', gender: '', currency: '' })

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (!formData.username || !formData.name || !formData.email || !formData.password || !formData.language || !formData.gender || !formData.currency) {
      alert('Please fill all fields')
      return
    }

    try {
      const res = await fetch('http://localhost:8000/api/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username: formData.username, user_full_name: formData.name, email: formData.email, password: formData.password, mobile: selectedCountry && formData.phone ? `+${selectedCountry.dialCode}${formData.phone}` : null, language: formData.language === 'ar' ? 'arabic' : 'english', gender: formData.gender, currency: formData.currency })
      })
      const data = await res.json()
      if (data.data?.id) {
        localStorage.setItem('verifyEmail', formData.email)
        onVerifyNeeded()
      } else {
        alert(data.message || 'Registration failed')
      }
    } catch (e) {
      alert('Network error')
    }
  }

  return (
    <form onSubmit={handleSubmit} className="row g-3">
      <div className="col-12 mb-4">
        <input type="text" className="form-control" placeholder="username" value={formData.username} onChange={(e) => setFormData({ ...formData, username: e.target.value })} />
      </div>
      <div className="col-12 mb-4">
        <input type="text" className="form-control" placeholder="full name" value={formData.name} onChange={(e) => setFormData({ ...formData, name: e.target.value })} />
      </div>
      <div className="col-12 mb-4">
        <input type="email" className="form-control" placeholder="E-mail" value={formData.email} onChange={(e) => setFormData({ ...formData, email: e.target.value })} />
      </div>
      <div className="col-12 mb-4">
        <label style={{ color: '#0d464c', fontWeight: 700, marginBottom: '10px', display: 'block' }}>Phone number</label>
        <select className="form-select mb-2" value={selectedCountry?.iso2 || ''} onChange={(e) => { const country = countries.find((c: any) => c.iso2 === e.target.value); onCountrySelect(country); }}>
          <option value="">Select country</option>
          {countries.map((c: any) => (
            <option key={c.iso2} value={c.iso2}>
              {c.name} (+{c.dialCode})
            </option>
          ))}
        </select>
        <input type="tel" className="form-control" placeholder="Phone number" value={formData.phone} onChange={(e) => setFormData({ ...formData, phone: e.target.value.replace(/\D/g, '') })} />
      </div>
      <div className="col-12 mb-4">
        <input type="password" className="form-control" placeholder="Password" value={formData.password} onChange={(e) => setFormData({ ...formData, password: e.target.value })} />
      </div>
      <div className="row mb-3">
        <div className="col-md-4">
          <select className="form-select" value={formData.language} onChange={(e) => setFormData({ ...formData, language: e.target.value })}>
            <option value="">Language</option>
            <option value="en">English</option>
            <option value="ar">العربية</option>
          </select>
        </div>
        <div className="col-md-4">
          <select className="form-select" value={formData.gender} onChange={(e) => setFormData({ ...formData, gender: e.target.value })}>
            <option value="">Gender</option>
            <option value="female">female</option>
            <option value="male">male</option>
          </select>
        </div>
        <div className="col-md-4 mb-4">
          <select className="form-select" value={formData.currency} onChange={(e) => setFormData({ ...formData, currency: e.target.value })}>
            <option value="">Currency</option>
            <option value="usd">USD</option>
            <option value="syp">SYP</option>
            <option value="aed">AED</option>
          </select>
        </div>
      </div>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <button type="submit" className="btn btn-lg w-100">Create Account</button>
      </div>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <button type="button" className="btn btn-lg w-100" onClick={onBack}>
          back
        </button>
      </div>
    </form>
  )
}

function ForgotPasswordForm({ onBack, onReset }: any) {
  const [email, setEmail] = useState('')

  const handleSendCode = async () => {
    if (!email) { alert('Please enter your email'); return; }
    try {
      const res = await fetch('http://localhost:8000/api/auth/forgot-password', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ email }) })
      const data = await res.json()
      if (data.data?.id || data.message?.includes('sent')) { localStorage.setItem('forgotEmail', email); onReset(); } else { alert(data.message || 'Could not send reset code'); }
    } catch (e) { alert('Network error'); }
  }

  return (
    <div>
      <p style={{ marginBottom: '15px', color: '#0d464c' }}>Enter your account email to receive a reset code</p>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <input type="email" className="form-control" placeholder="E-mail" value={email} onChange={(e) => setEmail(e.target.value)} />
      </div>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <button type="button" className="btn btn-lg w-100" onClick={handleSendCode}>
          Send Code
        </button>
      </div>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <button type="button" className="btn btn-lg w-100" onClick={onBack}>
          Back
        </button>
      </div>
    </div>
  )
}

function ResetPasswordForm({ onBack }: any) {
  const [code, setCode] = useState('')
  const [newPass, setNewPass] = useState('')
  const [confirmPass, setConfirmPass] = useState('')

  const handleReset = async () => {
    if (!code || !newPass || !confirmPass) { alert('Please fill all fields'); return; }
    if (newPass !== confirmPass) { alert('Passwords do not match'); return; }
    const email = localStorage.getItem('forgotEmail')
    try {
      const res = await fetch('http://localhost:8000/api/auth/reset-password', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ email, verification_code: code, password: newPass, password_confirmation: confirmPass }) })
      const data = await res.json()
      if (data.data?.token) { alert('Password updated. Please sign in.'); localStorage.removeItem('forgotEmail'); onBack(); } else { alert(data.message || 'Could not reset password'); }
    } catch (e) { alert('Network error'); }
  }

  return (
    <div>
      <p style={{ marginBottom: '15px', color: '#0d464c' }}>Enter the code sent to your email and new password</p>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <input type="text" className="form-control" placeholder="6-digit code" value={code} onChange={(e) => setCode(e.target.value)} />
      </div>
      <div className="col-12 mb-3">
        <input type="password" className="form-control" placeholder="New password" value={newPass} onChange={(e) => setNewPass(e.target.value)} />
      </div>
      <div className="col-12 mb-3">
        <input type="password" className="form-control" placeholder="Confirm new password" value={confirmPass} onChange={(e) => setConfirmPass(e.target.value)} />
      </div>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <button type="button" className="btn btn-lg w-100" onClick={handleReset}>
          Update Password
        </button>
      </div>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <button type="button" className="btn btn-lg w-100" onClick={onBack}>
          Back
        </button>
      </div>
    </div>
  )
}

function VerifyForm({ onBack }: any) {
  const [email, setEmail] = useState(localStorage.getItem('verifyEmail') || '')
  const [code, setCode] = useState('')

  const handleVerify = async () => {
    if (!code) { alert('Please enter the code'); return; }
    try {
      const res = await fetch('http://localhost:8000/api/auth/verify-email', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ email, verification_code: code }) })
      const data = await res.json()
      if (data.data?.token) { localStorage.setItem('token', data.data.token); localStorage.setItem('user', JSON.stringify(data.data.user || {})); localStorage.removeItem('verifyEmail'); window.location.href = '/'; } else { alert(data.message || 'Verification failed'); }
    } catch (e) { alert('Network error'); }
  }

  return (
    <div>
      <p style={{ marginBottom: '15px', color: '#0d464c' }}>A verification code was sent to your email. Enter it below:</p>
      <div className="col-12 mb-3">
        <input type="email" className="form-control" placeholder="E-mail" value={email} onChange={(e) => setEmail(e.target.value)} />
      </div>
      <div className="col-12 mb-3">
        <input type="text" className="form-control" placeholder="6-digit code" value={code} onChange={(e) => setCode(e.target.value)} />
      </div>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <button type="button" className="btn btn-lg w-100" onClick={handleVerify}>
          Verify Email
        </button>
      </div>
      <div className="col-12 d-flex justify-content-center px-0 mb-3">
        <button type="button" className="btn btn-lg w-100" onClick={onBack}>
          Back to Login
        </button>
      </div>
    </div>
  )
}
